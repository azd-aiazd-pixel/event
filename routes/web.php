<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\ParticipantController;
use App\Http\Controllers\Admin\NfcTagController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Store\StoreDashboardController;
use App\Http\Controllers\Store\StoreProductController;
use App\Http\Controllers\Store\StoreTerminalController;
use App\Http\Controllers\Store\StoreOrderController;
use App\Http\Controllers\Store\OrderQueueController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Participant\ParticipantStoreController;
use App\Http\Controllers\Participant\CartController;
use App\Http\Controllers\Participant\OrderController;
use App\Http\Controllers\Participant\WishlistController;
use App\Http\Controllers\Participant\ParticipantDashboardController;
use App\Http\Controllers\Store\StoreSettingsController;
use App\Http\Controllers\Store\RefundController;
use App\Http\Controllers\ProfileController;

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    
            
            Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

               Route::middleware('admin')
                    ->prefix('admin')           
                    ->name('admin.')            
                    ->group(function () {

                Route::get('/dashboard', [AdminDashboardController::class, 'globalDashboard'])->name('dashboard');   
                    Route::prefix('users')->name('users.')->group(function() {
                        Route::get('/create', [UserController::class, 'create'])->name('create');
                        Route::post('/', [UserController::class, 'store'])->name('store');
                        
                    });
                        
             Route::prefix('profile')->name('profile.')->group(function () {
            
                
                    Route::get('/', [ProfileController::class, 'edit'])->name('edit');
                    
                    Route::patch('/info', [ProfileController::class, 'updateInfo'])->name('update.info');
                    
                    Route::patch('/password', [ProfileController::class, 'updatePassword'])->name('update.password');
            
                  });

                        Route::resource('events', EventController::class);

                        Route::get('/events/{event}/dashboard', [AdminDashboardController::class, 'eventdashboard'])->name('events.dashboard');

                        Route::prefix('events/{event}/participants')->name('participants.')->group(function () {
                            
                            // liste des participants a un event
                            Route::get('/', [ParticipantController::class, 'index'])->name('index');
                            
                            // formulaire de création
                            Route::get('/create', [ParticipantController::class, 'create'])->name('create');
                            
                            // enregistrement d un nouveau participant 
                            Route::post('/', [ParticipantController::class, 'store'])->name('store');
                            
                            //  page d edition d un participant   
                            Route::get('/{participant}/edit', [ParticipantController::class, 'edit'])->name('edit');
                            
                            // mise à jour du paticip 
                            Route::put('/{participant}', [ParticipantController::class, 'update'])->name('update');
                            
                            // suppression
                            Route::delete('/{participant}', [ParticipantController::class, 'destroy'])->name('destroy');

                            //  traitement du fichier d'importation 
                            Route::post('/import', [ParticipantController::class, 'import'])->name('import');
                        });






                                // Gestion des Boutiques d'un événement
                        Route::prefix('events/{event}/stores')->name('stores.')->group(function () {


                       Route::get('/{store}/dashboard', [AdminDashboardController::class, 'storeDashboard'])->name('dashboard');
                            
                            // Liste des boutiques de l'event
                            Route::get('/', [StoreController::class, 'index'])->name('index');
                            
                            // Formulaire de création d'une boutique
                            Route::get('/create', [StoreController::class, 'create'])->name('create');
                            
                            // Enregistrement de la boutique
                            Route::post('/', [StoreController::class, 'store'])->name('store');
                            
                            // Page d'édition d'une boutique
                            Route::get('/{store}/edit', [StoreController::class, 'edit'])->name('edit');
                            
                            // Mise à jour de la boutique
                            Route::put('/{store}', [StoreController::class, 'update'])->name('update');
                            
                            // Suppression de la boutique
                            Route::delete('/{store}', [StoreController::class, 'destroy'])->name('destroy');
                        });

                        // Route AJAX pour Select2 (recherche de vendeurs)
                       
                        Route::get('/users/search-vendors', [StoreController::class, 'searchUsers'])->name('users.search-vendors');






                        Route::prefix('stores/{store}/products')->name('stores.products.')->group(function () {
                            
                          
                            Route::get('/', [ProductController::class, 'index'])->name('index');

                           
                            Route::get('/create', [ProductController::class, 'create'])->name('create');

                            Route::post('/', [ProductController::class, 'store'])->name('store');

                            Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit');

                            Route::put('/{product}', [ProductController::class, 'update'])->name('update');

                            Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');

                            Route::post('/import', [ProductController::class, 'import'])->name('import');
                            Route::get('/export', [ProductController::class, 'export'])->name('export');
                        });

    });
  
    Route::middleware('store')
        ->prefix('store')
        ->name('store.')
        ->group(function () {
            
         Route::post('/orders/{order}/complete', [OrderQueueController::class, 'complete'])->name('orders.complete');
         Route::post('/orders/{order}/cancel', [OrderQueueController::class, 'cancel'])->name('orders.cancel');

           Route::get('/select', [StoreDashboardController::class, 'index'])->name('select');

    


        Route::prefix('{store}')->group(function () {
                    Route::prefix('profile')->name('profile.')->group(function () {
            
                
                                Route::get('/', [ProfileController::class, 'edit'])->name('edit');
                                
                                Route::patch('/info', [ProfileController::class, 'updateInfo'])->name('update.info');
                                
                                Route::patch('/password', [ProfileController::class, 'updatePassword'])->name('update.password');
                        
                            });









               Route::get('/queue', [OrderQueueController::class, 'index'])->name('queue.index');

            
      Route::get('/orders', [StoreOrderController::class, 'index'])->name('orders.index');





      Route::prefix('refunds')->name('refunds.')->group(function () {
               
                Route::get('/', [RefundController::class, 'index'])->name('index');
                
            
                Route::post('/search', [RefundController::class, 'search'])->name('search');
                
           
                Route::post('/process', [RefundController::class, 'process'])->name('process');
            });


            Route::get('/dashboard', [StoreDashboardController::class, 'dashboard'])->name('dashboard');

            Route::get('/products/create', [StoreProductController::class, 'create'])->name('products.create');
        
        Route::post('/products', [StoreProductController::class, 'store'])->name('products.store');



        Route::get('/terminal', [StoreTerminalController::class, 'index'])->name('terminal.index');
        Route::post('/terminal/pay', [StoreTerminalController::class, 'processPayment'])->name('terminal.pay');
        
        Route::post('/terminal/pickup/scan', [StoreTerminalController::class, 'scanForPickup'])->name('terminal.pickup.scan');
        Route::post('/terminal/pickup/collect', [StoreTerminalController::class, 'markAsCollected'])->name('terminal.pickup.collect');


           Route::get('/settings', [StoreSettingsController::class, 'edit'])->name('settings.edit');
             Route::put('/settings/identity', [StoreSettingsController::class, 'updateIdentity'])->name('settings.update.identity');
                Route::put('/settings/workflow', [StoreSettingsController::class, 'updateWorkflow'])->name('settings.update.workflow');
                Route::put('/settings/theme', [StoreSettingsController::class, 'update'])->name('settings.update');


            Route::prefix('products')->name('products.')->group(function () {
                Route::get('/', [StoreProductController::class, 'index'])->name('index');
                Route::post('/', [StoreProductController::class, 'store'])->name('store');
                 Route::get('/create', [StoreProductController::class, 'create'])->name('create');
                 Route::get('/{product}/edit', [StoreProductController::class, 'edit'])->name('edit');
                Route::put('/{product}', [StoreProductController::class, 'update'])->name('update');
      
             Route::post('/import', [StoreProductController::class, 'import'])->name('import');

             
            });

        });

    });


    Route::middleware('participant') 
         ->name('participant.')
        ->group(function () {

            Route::get('/dashboard', [ParticipantDashboardController::class, 'dashboard'])->name('dashboard');
    
            Route::get('/stores', [ParticipantStoreController::class, 'index'])->name('stores.index');  
            Route::get('/stores/{store}', [ParticipantStoreController::class, 'show'])->name('stores.show');

  Route::prefix('profile')->name('profile.')->group(function () {
            
                
                    Route::get('/', [ProfileController::class, 'edit'])->name('edit');
                    
                    Route::patch('/info', [ProfileController::class, 'updateInfo'])->name('update.info');
                    
                    Route::patch('/password', [ProfileController::class, 'updatePassword'])->name('update.password');
            
                  });

            Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
            Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');


            Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
            Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');

            Route::post('/wishlist/{product}/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
            Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    });







});