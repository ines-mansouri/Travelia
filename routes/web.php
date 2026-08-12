<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\Packages\PostController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\HajjController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [WelcomeController::class, 'index'])->name('home');
Route::get('destinations/{destination}', [PostController::class, 'show'])->name('desti.show');
Route::get('destinations/{destination}/details', [PostController::class, 'details'])->name('desti.details');

Auth::routes(['verify' => true]);

Route::middleware(['auth'])->group(function () {

    // Reviews
    Route::post('destinations/{destination}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Wishlist
    Route::get('wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('wishlist/{destination}/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::post('wishlist/{destination}', [WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('wishlist/{destination}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');
});

Route::group(['middleware' => ['isVerified']], function () {
    Route::get('email-verification/error', [RegisterController::class, 'getVerificationError'])->name('email-verification.error');
    Route::get('email-verification/check/{token}', [RegisterController::class, 'getVerification'])->name('email-verification.check');
});

Route::get('/flights', [\App\Http\Controllers\FlightsController::class, 'index'])->name('flights');
Route::get('/flights/autocomplete', [\App\Http\Controllers\FlightsController::class, 'autocomplete'])->name('flights.autocomplete');
Route::post('/flights/search', [\App\Http\Controllers\FlightsController::class, 'search'])->name('flights.search');
Route::middleware('auth')->post('/flights/checkout', [\App\Http\Controllers\FlightBookingController::class, 'createCheckoutSession'])->name('flights.checkout');




Route::get('/flights/booking/{booking}/success', [\App\Http\Controllers\FlightBookingController::class, 'success'])->name('flights.booking.success');
Route::get('/flights/booking/{booking}/invoice', [\App\Http\Controllers\FlightBookingController::class, 'downloadInvoice'])->name('flights.booking.invoice');
Route::delete('/flights/booking/{booking}/cancel', [\App\Http\Controllers\FlightBookingController::class, 'cancel'])->name('flights.booking.cancel');

Route::get('/hotels', [\App\Http\Controllers\HotelsController::class, 'index'])->name('hotels.index');
Route::get('/hotels/autocomplete', [\App\Http\Controllers\HotelsController::class, 'autocomplete'])->name('hotels.autocomplete');
Route::post('/hotels/search', [\App\Http\Controllers\HotelsController::class, 'search'])->name('hotels.search');
Route::get('/hotels/{hotel}', [\App\Http\Controllers\HotelsController::class, 'show'])->name('hotels.show');




Route::get('/destinations', [\App\Http\Controllers\PackagesController::class, 'index'])->name('packages');

Route::get('/hajj/{id}/details', [HajjController::class, 'details'])->name('hajj.details');

Route::get('/hajj', [HajjController::class, 'index'])->name('hajj');
Route::middleware('auth')->get('/hajj/bookings/{booking}/success', [\App\Http\Controllers\HajjBookingController::class, 'success'])->name('hajj.success');
Route::middleware('auth')->post('/hajj/{id}/book', [\App\Http\Controllers\HajjBookingController::class, 'store'])->name('hajj.book');
Route::get('/hajj/{id}', [HajjController::class, 'show'])->name('hajj.show');

Route::post('/newsletter/subscribe', [\App\Http\Controllers\NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

Route::middleware('auth')->get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/profile/avatar', [\App\Http\Controllers\ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::get('/bookings/{booking}', [\App\Http\Controllers\BookingController::class, 'show'])->name('bookings.show');
    Route::delete('/bookings/{booking}/cancel', [\App\Http\Controllers\BookingController::class, 'cancel'])->name('bookings.cancel');
});

Route::get('/contact', [WelcomeController::class, 'contact'])->name('contact');

Route::get('/Bali', [WelcomeController::class, 'Bali'])->name('Bali');

Route::get('/cart', [WelcomeController::class, 'cart'])->name('cart');
Route::post('/cart/add/{destination}', function (\App\Destinations $destination) {
    session(['cart_destination_id' => $destination->id]);
    return redirect()->route('cart');
})->name('cart.add');

Route::get('/checkout', [WelcomeController::class, 'checkout'])->name('checkout');

// Destination bookings (Stripe Checkout Sessions, like flights)
Route::middleware('auth')->post('/destinations/checkout', [\App\Http\Controllers\DestinationBookingController::class, 'createCheckoutSession'])->name('destinations.checkout');
Route::middleware('auth')->get('/destinations/booking/{booking}/success', [\App\Http\Controllers\DestinationBookingController::class, 'success'])->name('destinations.booking.success');

Route::middleware('auth')->match(['get', 'post'], '/checkout/store', [CheckoutController::class, 'checkout'])->name('checkout.store');

Route::post('/stripe/pay', [\App\Http\Controllers\StripePaymentController::class, 'stripePost'])->name('stripe.post');

// Post form data
Route::post('/contact', [ContactUsController::class, 'ContactUs'])->name('contact.store');

Route::get('/stripe', [WelcomeController::class, 'stripe'])->name('stripe');

Route::delete('/cart/{id}/remove', [CartController::class, 'removeItem'])->name('cart.remove');

Route::get('/send-email', [MailController::class, 'sendEmail']);

// Stripe Webhook
Route::post('/stripe/webhook', [\App\Http\Controllers\StripeWebhookController::class, 'handle'])->name('stripe.webhook');

// Booking Reviews (polymorphic — for completed journeys)
Route::middleware('auth')->post('/booking-reviews', [\App\Http\Controllers\BookingReviewController::class, 'store'])->name('booking-reviews.store');

// Testimonials
Route::middleware('auth')->prefix('testimonials')->group(function () {
    Route::get('/', [\App\Http\Controllers\TestimonialController::class, 'index'])->name('testimonials.index');
    Route::post('/', [\App\Http\Controllers\TestimonialController::class, 'store'])->name('testimonials.store');
    Route::delete('/{testimonial}', [\App\Http\Controllers\TestimonialController::class, 'destroy'])->name('testimonials.destroy');
});

// SEO
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Locale switching
Route::get('/locale/{locale}', function (string $locale) {
    if (in_array($locale, ['en', 'ar'])) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('locale.switch');

// Currency switching
Route::get('/currency/{currency}', function (string $currency) {
    $available = config('currencies.available', []);
    if (in_array($currency, $available)) {
        session()->put('currency', $currency);
    }
    return redirect()->back();
})->name('currency.switch');
