<?php
namespace App\Http\Controllers\Admin;
use \App\Enum\NfcTagStatus;
use \Illuminate\Support\Str;
use App\Models\Participant;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Enum\TransactionType;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Event;
use App\Models\User;
use  Illuminate\Support\Facades\Hash;
use App\Enum\Role;
use App\Models\Transaction;
class ParticipantController extends Controller
{
   // Affiche la liste des participants d'un événement
public function index(Request $request, Event $event)
    {
     

        $query = Participant::where('event_id', $event->id)
            ->with(['user']); 

      
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nfc_code', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($sq) use ($search) {
                      $sq->where('email', 'like', "%{$search}%");
                  });
            });
        }

        $participants = $query->latest()->paginate(15)->withQueryString();

        return view('admin.participants.index', [
            'participants' => $participants,
            'event'        => $event,
        ]);
    }

// Affiche le formulaire de création d'un participant pour un événement donné
public function create(Event $event)
    {
        
        return view('admin.participants.create', [
            'event' => $event
        ]);
    }




    // ajouter  participant à un événement
   public function store(Request $request, Event $event)
    {
        $request->validate([
            'nfc_code' => ['required', 'string', 'unique:participants,nfc_code'],
            'balance'  => ['required', 'numeric', 'min:0'],
        ]);

        $uniqueSuffix = Str::random(8); 
        $email = $uniqueSuffix . "@event.ma";
        $password = $uniqueSuffix;
        $name = "Participant";

        try {
            return DB::transaction(function () use ($request, $event, $email, $password, $name) {
                
               
                $user = User::create([
                    'name'     => $name,
                    'email'    => $email,
                    'password' => Hash::make($password),
                    'role'     => Role::Participant, 
                ]);

                $participant = Participant::create([
                    'user_id'  => $user->id,
                    'event_id' => $event->id,
                    'nfc_code' => $request->nfc_code,
                    'balance'  => $request->balance,
                ]);

                if ($request->balance > 0) {
                    Transaction::create([
                        'participant_id' => $participant->id,
                        'event_id'       => $event->id,
                        'order_id'       => null, 
                        'amount'         => $request->balance,
                        'type'           => TransactionType::TopUp,
                    ]);
                }

                return redirect()
                    ->route('admin.participants.index', $event->id)
                    ->with('success', 'Participant enregistré avec succès.');
            });

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la création du compte.')->withInput();
        }
    }





 // Affiche le formulaire d'édition d'un participant
    public function edit(Event $event, Participant $participant)
    {
      
        if ($participant->event_id !== $event->id) {
            abort(404);
        }

        $participant->load('user');

        return view('admin.participants.edit', [
            'event'       => $event,
            'participant' => $participant,
        ]);
    }


// Met à jour les informations d'un participant
public function update(Request $request, Event $event, Participant $participant)
    {
        if ($participant->event_id !== $event->id) {
            abort(403, 'Action non autorisée pour cet événement.');
        }

        $validated = $request->validate([
            'nfc_code' => ['required', 'string', 'unique:participants,nfc_code,' . $participant->id],
            'balance'  => ['required', 'numeric', 'min:0'],
        ]);

        try {
           
            return DB::transaction(function () use ($validated, $event, $participant) {
                
                
                $oldBalance = $participant->balance;
                $newBalance = $validated['balance'];
                $difference = $newBalance - $oldBalance;

                
                $participant->update([
                    'nfc_code' => $validated['nfc_code'],
                    'balance'  => $newBalance,
                ]);

                
                if ($difference > 0) {
                   
                    Transaction::create([
                        'participant_id' => $participant->id,
                        'event_id'       => $event->id,
                        'order_id'       => null,
                        'amount'         => $difference, 
                        'type'           => TransactionType::TopUp,
                    ]);
                } elseif ($difference < 0) {
                    
                    Transaction::create([
                        'participant_id' => $participant->id,
                        'event_id'       => $event->id,
                        'order_id'       => null,
                        'amount'         => abs($difference), 
                        'type'           => TransactionType::Refund,
                    ]);
                }
             

                return redirect()
                    ->route('admin.participants.index', $event->id)
                    ->with('success', 'Modifications et régularisation comptable enregistrées.');
            });

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage())->withInput();
        }
    }



// archive un participant et son compte utilisateur associé (soft delete)
public function destroy(Event $event, Participant $participant)
{
    
    if ($participant->event_id !== $event->id) {
        abort(403, 'Action non autorisée.');
    }

    try {
        return DB::transaction(function () use ($participant) {
            $user = $participant->user; 
           $participant->delete();
        
            if ($user) {
                $user->delete();
            }

            return redirect()
                ->route('admin.participants.index', $participant->event_id)
                ->with('success', 'Participant et compte utilisateur archivés avec succès.');
        });
    } catch (\Exception $e) {
        return back()->with('error', 'Erreur lors de l\'archivage du participant.');
    }
}

public function import(Request $request, Event $event)
{
    $request->validate([
        'file' => ['required', 'file', 'max:2048'],
        'initial_balance' => ['nullable', 'numeric', 'min:0'], 
    ]);

    $initialBalance = $request->input('initial_balance', 0);
    $file = $request->file('file');

    if (($handle = fopen($file->getPathname(), 'r')) !== false) {
        
        $imported = 0;
        $duplicates = 0;

        try {
            DB::beginTransaction(); 

            while (($line = fgets($handle)) !== false) {
                $code = trim($line);
                if (empty($code)) continue;

                if (Participant::where('nfc_code', $code)->exists()) {
                    $duplicates++;
                    continue;
                }

                $token = Str::random(8);
                $email = $token . "@event.ma"; 

              
                $user = User::create([
                    'name'     => "Participant",
                    'email'    => $email,
                    'password' => Hash::make($token), 
                    'role'     => Role::Participant,
                ]);

       
                $participant = Participant::create([
                    'user_id'  => $user->id,
                    'event_id' => $event->id,
                    'nfc_code' => $code,
                    'balance'  => $initialBalance, 
                ]);

               
                if ($initialBalance > 0) {
                    Transaction::create([
                        'participant_id' => $participant->id,
                        'event_id'       => $event->id,
                        'order_id'       => null,
                        'amount'         => $initialBalance,
                        'type'           => TransactionType::TopUp,
                    ]);
                }

                $imported++;
            }

            DB::commit(); 
            fclose($handle);

            $message = "Import réussi : {$imported} participants ajoutés.";
            if ($duplicates > 0) $message .= " ({$duplicates} doublons ignorés).";

            return redirect()->route('admin.participants.index', $event->id)->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack(); 
            if (isset($handle)) fclose($handle);
            return back()->with('error', "Erreur : " . $e->getMessage());
        }
    }
}



}