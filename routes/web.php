<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ArticleGroupController;
use App\Http\Controllers\Auth as AuthController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\Clients;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\ContractTypeController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\CreditNoteController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerGroupController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EstimateController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\LeadSourceController;
use App\Http\Controllers\LeadStatusController;
use App\Http\Controllers\Listing;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentModeController;
use App\Http\Controllers\PredefinedReplyController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductGroupController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaxRateController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TicketPriorityController;
use App\Http\Controllers\TicketReplyController;
use App\Http\Controllers\TicketStatusController;
use App\Http\Controllers\TranslationManagerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Web;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Redirect::to('/login');
})->name('redirect.login');

Auth::routes(['verify' => true]);

/** account verification route */
Route::get('activate', [AuthController\RegisterController::class, 'verifyAccount'])->name('activate');

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('articles', [Web\ArticleController::class, 'index']);
Route::get('search-article', [Web\ArticleController::class, 'searchArticle'])->name('article.search');
Route::get('articles/{article}', [Web\ArticleController::class, 'show']);

// Impersonate admin routes
Route::get('/impersonate/{userId}', [MemberController::class, 'impersonate'])->name('impersonate');
Route::get('/impersonate-leave', [MemberController::class, 'impersonateLeave'])->name('impersonate.leave');

// Impersonate customer routes
Route::get('/contacts-impersonate/{userId}', [ContactController::class, 'impersonate'])->name('contacts.impersonate');
Route::get('/contacts-impersonate-leave',
    [ContactController::class, 'impersonateLeave'])->name('contacts.impersonate.leave');

//Header Notification
Route::get('/get-notifications', [NotificationController::class, 'index']);
Route::post('/notification/{notification}/read',
    [NotificationController::class, 'readNotification'])->name('notifications.read');
Route::post('/read-all-notification',
    [NotificationController::class, 'readAllNotification'])->name('notifications.read.all');

Route::middleware(['auth', 'xss', 'checkUserStatus', 'checkRoleUrl'])->prefix('admin')->group(function () {
        // Dashboard route
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Customer groups routes
    Route::middleware('permission:manage_customer_groups')->group(function () {
        Route::get('customer-groups', [CustomerGroupController::class, 'index'])->name('customer-groups.index');
        Route::post('customer-groups', [CustomerGroupController::class, 'store'])->name('customer-groups.store');
        Route::get('customer-groups/create', [CustomerGroupController::class, 'create'])->name('customer-groups.create');
        Route::put('customer-groups/{customerGroup}',
                [CustomerGroupController::class, 'update'])->name('customer-groups.update');
        Route::get('customer-groups/{customerGroup}', [CustomerGroupController::class, 'show'])->name('customer-groups.show');
        Route::delete('customer-groups/{customerGroup}',
                [CustomerGroupController::class, 'destroy'])->name('customer-groups.destroy');
        Route::get('customer-groups/{customerGroup}/edit',
                [CustomerGroupController::class, 'edit'])->name('customer-groups.edit');
    });

    // Tags module routes
    Route::middleware('permission:manage_tags')->group(function () {
        Route::get('tags', [TagController::class, 'index'])->name('tags.index');
        Route::post('tags', [TagController::class, 'store'])->name('tags.store');
        Route::get('tags/{tag}/edit', [TagController::class, 'edit'])->name('tags.edit');
        Route::put('tags/{tag}', [TagController::class, 'update'])->name('tags.update');
        Route::delete('tags/{tag}', [TagController::class, 'destroy'])->name('tags.destroy');
        Route::get('tags/{tag}', [TagController::class, 'show'])->name('tags.show');
    });

    // Customer routes
    Route::middleware('permission:manage_customers')->group(function () {
        Route::get('customers', [CustomerController::class, 'index'])->name('customers.index');
        Route::get('customers/create', [CustomerController::class, 'create'])->name('customers.create');
        Route::post('customers', [CustomerController::class, 'store'])->name('customers.store');
        Route::get('customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
        Route::get('customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
        Route::put('customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
        Route::delete('customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');
        Route::get('customers/{customer}/{group}', [CustomerController::class, 'show']);
        Route::post('customers/{customer}/{group}/notes-count',
                [CustomerController::class, 'getNotesCount']);
        Route::get('search-customers',
                [CustomerController::class, 'searchCustomer'])->name('customers.search.customer');
        Route::post('add-customer-address',
                [CustomerController::class, 'addCustomerAddress'])->name('add.customer.address');
    });

    // Contacts routes
    Route::get('contacts', [ContactController::class, 'index'])->name('contacts.index');
    Route::get('contacts/create/{customerId?}', [ContactController::class, 'create'])->name('contacts.create');
    Route::post('contacts', [ContactController::class, 'store'])->name('contacts.store');
    Route::get('contacts/{contact}', [ContactController::class, 'show'])->name('contacts.show');
    Route::get('contacts/{contact}/edit', [ContactController::class, 'edit'])->name('contacts.edit');
    Route::post('contacts/{contact}', [ContactController::class, 'update'])->name('contacts.update');
    Route::delete('contacts/{contact}', [ContactController::class, 'destroy'])->name('contacts.destroy');
    Route::post('contacts/{contact}/active-deactive',
            [ContactController::class, 'activeDeActiveContact'])->name('contacts.activeDeActiveContact');

    // Notes routes
    Route::get('notes', [NoteController::class, 'index'])->name('notes.index');
    Route::post('notes', [NoteController::class, 'store'])->name('notes.store');
    Route::get('notes/{note}/edit', [NoteController::class, 'edit'])->name('notes.edit');
    Route::put('notes/{note}', [NoteController::class, 'update'])->name('notes.update');
    Route::delete('notes/{note}', [NoteController::class, 'destroy'])->name('notes.destroy');

    // Reminders routes
    Route::get('reminder', [ReminderController::class, 'index'])->name('reminder.index');
    Route::post('reminder', [ReminderController::class, 'store'])->name('reminder.store');
    Route::get('reminder/{reminder}/edit', [ReminderController::class, 'edit'])->name('reminder.edit');
    Route::put('reminder/{reminder}', [ReminderController::class, 'update'])->name('reminder.update');
    Route::delete('reminder/{reminder}', [ReminderController::class, 'destroy'])->name('reminder.destroy');

    // Comments routes
    Route::get('comments', [CommentController::class, 'index'])->name('comments.index');
    Route::post('comments', [CommentController::class, 'store'])->name('comments.store');
    Route::put('comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::get('comments/{comment}/edit', [CommentController::class, 'edit'])->name('comments.edit');

    // Departments routes
    Route::middleware('permission:manage_departments')->group(function () {
        Route::get('departments', [DepartmentController::class, 'index'])->name('departments.index');
        Route::post('departments', [DepartmentController::class, 'store'])->name('departments.store');
        Route::get('departments/{department}/edit', [DepartmentController::class, 'edit'])->name('departments.edit');
        Route::put('departments/{department}', [DepartmentController::class, 'update'])->name('departments.update');
        Route::delete('departments/{department}', [DepartmentController::class, 'destroy'])->name('departments.destroy');
    });

    // Article Groups module routes
    Route::middleware('permission:manage_article_groups')->group(function () {
        Route::get('article-groups', [ArticleGroupController::class, 'index'])->name('article-groups.index');
        Route::post('article-groups', [ArticleGroupController::class, 'store'])->name('article-groups.store');
        Route::get('article-groups/{articleGroup}/edit',
                [ArticleGroupController::class, 'edit'])->name('article-groups.edit');
        Route::put('article-groups/{articleGroup}', [ArticleGroupController::class, 'update'])->name('article-groups.update');
        Route::delete('article-groups/{articleGroup}',
                [ArticleGroupController::class, 'destroy'])->name('article-groups.destroy');
    });

    // Expenses Categories routes
    Route::middleware('permission:manage_expense_category')->group(function () {
        Route::get('expense-categories', [ExpenseCategoryController::class, 'index'])->name('expense-categories.index');
        Route::post('expense-categories', [ExpenseCategoryController::class, 'store'])->name('expense-categories.store');
        Route::get('expense-categories/{expenseCategory}/edit',
                [ExpenseCategoryController::class, 'edit'])->name('expense-categories.edit');
        Route::put('expense-categories/{expenseCategory}',
                [ExpenseCategoryController::class, 'update'])->name('expense-categories.update');
        Route::delete('expense-categories/{expenseCategory}',
                [ExpenseCategoryController::class, 'destroy'])->name('expense-categories.destroy');
    });

    // Predefined Replies routes
    Route::middleware('permission:manage_predefined_replies')->group(function () {
        Route::get('predefined-replies', [PredefinedReplyController::class, 'index'])->name('predefinedReplies.index');
        Route::post('predefined-replies', [PredefinedReplyController::class, 'store'])->name('predefinedReplies.store');
        Route::get('predefined-replies/{predefinedReply}/edit',
                [PredefinedReplyController::class, 'edit'])->name('predefinedReplies.edit');
        Route::put('predefined-replies/{predefinedReply}',
                [PredefinedReplyController::class, 'update'])->name('predefinedReplies.update');
        Route::delete('predefined-replies/{predefinedReply}',
                [PredefinedReplyController::class, 'destroy'])->name('predefinedReplies.destroy');
        Route::get('predefined-replies/{predefinedReply}',
                [PredefinedReplyController::class, 'show'])->name('predefinedReplies.show');
    });

    // Services routes
    Route::middleware('permission:manage_services')->group(function () {
        Route::get('services', [ServiceController::class, 'index'])->name('services.index');
        Route::post('services', [ServiceController::class, 'store'])->name('services.store');
        Route::get('services/{service}/edit', [ServiceController::class, 'edit'])->name('services.edit');
        Route::put('services/{service}', [ServiceController::class, 'update'])->name('services.update');
        Route::delete('services/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');
    });

    // Products routes
    Route::middleware('permission:manage_items')->group(function () {
        Route::get('products', [ProductController::class, 'index'])->name('products.index');
        Route::post('products', [ProductController::class, 'store'])->name('products.store');
        Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    });

    // Tax Rates routes
    Route::middleware('permission:manage_tax_rates')->group(function () {
        Route::get('tax-rates', [TaxRateController::class, 'index'])->name('tax-rates.index');
        Route::post('tax-rates', [TaxRateController::class, 'store'])->name('tax-rates.store');
        Route::get('tax-rates/{taxRate}/edit', [TaxRateController::class, 'edit'])->name('tax-rates.edit');
        Route::put('tax-rates/{taxRate}', [TaxRateController::class, 'update'])->name('tax-rates.update');
        Route::delete('tax-rates/{taxRate}', [TaxRateController::class, 'destroy'])->name('tax-rates.destroy');
    });

    // Articles routes
    Route::middleware('permission:manage_articles')->group(function () {
        Route::get('articles', [ArticleController::class, 'index'])->name('articles.index');
        Route::get('articles/create', [ArticleController::class, 'create'])->name('articles.create');
        Route::post('articles', [ArticleController::class, 'store'])->name('articles.store');
        Route::get('articles/{article}', [ArticleController::class, 'show'])->name('articles.show');
        Route::get('articles/{article}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
        Route::post('articles/{article}', [ArticleController::class, 'update'])->name('articles.update');
        Route::delete('articles/{article}', [ArticleController::class, 'destroy'])->name('articles.destroy');
        Route::post('articles/{article}/active-deactive-article',
                [ArticleController::class, 'activeDeActiveInternalArticle'])->name('active.deactive.article');
        Route::post('articles/{article}/active-deactive-disabled',
                [ArticleController::class, 'activeDeActiveDisabled'])->name('active.deactive.disabled');
        Route::get('attachment-download/{article}', [ArticleController::class, 'downloadMedia']);
    });

    // Product Groups routes
    Route::middleware('permission:manage_items_groups')->group(function () {
        Route::get('product-groups', [ProductGroupController::class, 'index'])->name('product-groups.index');
        Route::post('product-groups', [ProductGroupController::class, 'store'])->name('product-groups.store');
        Route::get('product-groups/{productGroup}/edit',
                [ProductGroupController::class, 'edit'])->name('product-groups.edit');
        Route::put('product-groups/{productGroup}',
                [ProductGroupController::class, 'update'])->name('product-groups.update');
        Route::delete('product-groups/{productGroup}',
                [ProductGroupController::class, 'destroy'])->name('product-groups.destroy');
    });

    // Announcements routes
    Route::middleware('permission:manage_announcements')->group(function () {
        Route::get('announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
        Route::post('announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
        Route::get('announcements/{announcement}', [AnnouncementController::class, 'show'])->name('announcements.show');
        Route::get('announcements/{announcement}/edit', [AnnouncementController::class, 'edit'])->name('announcements.edit');
        Route::put('announcements/{announcement}', [AnnouncementController::class, 'update'])->name('announcements.update');
        Route::delete('announcements/{announcement}',
                [AnnouncementController::class, 'destroy'])->name('announcements.destroy');
        Route::post('announcements/{announcement}/active-deactive-client',
                [AnnouncementController::class, 'activeDeActiveClient'])->name('announcement.active.deactive.client');
        Route::get('announcement-detail/{announcement}',
                [AnnouncementController::class, 'getAnnouncementDetails'])->name('announcement.details');
        Route::post('announcements/{announcement}/change-status',
                [AnnouncementController::class, 'statusChange'])->name('announcements.status.change');
    });

    // Calendar routes
    Route::middleware('permission:manage_calenders')->group(function () {
        Route::get('calendars', [CalendarController::class, 'index'])->name('calendars.index');
        Route::get('calendar-list', [CalendarController::class, 'calendarList']);
    });

    // Contracts type routes
    Route::middleware('permission:manage_contracts_types')->group(function () {
        Route::get('contract-types', [ContractTypeController::class, 'index'])->name('contract-types.index');
        Route::post('contract-types', [ContractTypeController::class, 'store'])->name('contract-types.store');
        Route::get('contract-types/{contractType}/edit',
                [ContractTypeController::class, 'edit'])->name('contract-types.edit');
        Route::put('contract-types/{contractType}', [ContractTypeController::class, 'update'])->name('contract-types.update');
        Route::delete('contract-types/{contractType}',
                [ContractTypeController::class, 'destroy'])->name('contract-types.destroy');
    });

    // Member routes
    Route::middleware('permission:manage_staff_member')->group(function () {
        Route::get('members', [MemberController::class, 'index'])->name('members.index');
        Route::post('members', [MemberController::class, 'store'])->name('members.store');
        Route::get('members/create', [MemberController::class, 'create'])->name('members.create');
        Route::get('members/{member}/edit', [MemberController::class, 'edit'])->name('members.edit');
        Route::put('members/{member}', [MemberController::class, 'update'])->name('members.update');
        Route::get('members/{member}', [MemberController::class, 'show'])->name('members.show');
        Route::get('members/{member}/{group}', [MemberController::class, 'show']);
        Route::delete('members/{member}', [MemberController::class, 'destroy'])->name('members.destroy');
        Route::post('members/{member}/active-deactive-administrator',
                [MemberController::class, 'activeDeActiveAdministrator'])->name('members.active.deactive');
        Route::post('members/{member}/email-send',
                [MemberController::class, 'resendEmailVerification'])->name('email-send');
        Route::post('members/{member}/email-verify',
                [MemberController::class, 'emailVerified'])->name('email-verify');
    });

    // Expenses routes
    Route::middleware('permission:manage_expenses')->group(function () {
        Route::get('expenses', [ExpenseController::class, 'index'])->name('expenses.index');
        Route::get('expenses/create/{customerId?}', [ExpenseController::class, 'create'])->name('expenses.create');
        Route::post('expenses', [ExpenseController::class, 'store'])->name('expenses.store');
        Route::get('expenses/{expense}', [ExpenseController::class, 'show'])->name('expenses.show');
        Route::get('expenses/{expense}/edit', [ExpenseController::class, 'edit'])->name('expenses.edit');
        Route::put('expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
        Route::delete('expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');
        Route::get('expense-attachment-download/{expense}', [ExpenseController::class, 'downloadMedia']);
        Route::get('expenses/{expense}/comments-count',
                [ExpenseController::class, 'getCommentsCount'])->name('expense.comments.count');
        Route::get('expenses/{expense}/{group}', [ExpenseController::class, 'show']);
        Route::post('expenses/{expense}/{group}/notes-count',
                [ExpenseController::class, 'getNotesCount']);
        Route::get('expense-download-media/{mediaItem}',
                [ExpenseController::class, 'download'])->name('expense.download.media');
        Route::get('expenses-category-chart',
                [ExpenseController::class, 'expenseCategoryByChart'])->name('expenses.expenseCategoryChart');
    });

    // Leads routes
    Route::middleware('permission:manage_leads')->group(function () {
        Route::get('leads', [LeadController::class, 'index'])->name('leads.index');
        Route::get('leads/create/{customerId?}', [LeadController::class, 'create'])->name('leads.create');
        Route::post('leads', [LeadController::class, 'store'])->name('leads.store');
        Route::get('leads/{lead}', [LeadController::class, 'show'])->name('leads.show');
        Route::get('leads/{lead}/edit', [LeadController::class, 'edit'])->name('leads.edit');
        Route::put('leads/{lead}', [LeadController::class, 'update'])->name('leads.update');
        Route::delete('leads/{lead}', [LeadController::class, 'destroy'])->name('leads.destroy');
        Route::put('leads/{lead}/status/{status}',
                [LeadController::class, 'changeStatus'])->name('leads.changeStatus');
        Route::get('leads-kanban-list', [LeadController::class, 'kanbanList'])->name('leads.kanbanList');
        Route::post('contact-as-per-customer',
                [LeadController::class, 'contactAsPerCustomer'])->name('leads.contactAsPerCustomer');
        Route::get('leads/{lead}/{group}', [LeadController::class, 'show']);
        Route::post('leads/{lead}/{group}/notes-count',
                [LeadController::class, 'getNotesCount']);
        Route::post('lead-convert-customer',
                [CustomerController::class, 'leadConvertToCustomer'])->name('lead.convert.customer');
        Route::get('leads-convert-chart',
                [LeadController::class, 'leadConvertChart'])->name('leads.leadConvertChart');
    });

    // Goal routes
    Route::middleware('permission:manage_goals')->group(function () {
        Route::get('goals', [GoalController::class, 'index'])->name('goals.index');
        Route::post('goals', [GoalController::class, 'store'])->name('goals.store');
        Route::get('goals/create', [GoalController::class, 'create'])->name('goals.create');
        Route::put('goals/{goal}', [GoalController::class, 'update'])->name('goals.update');
        Route::get('goals/{goal}', [GoalController::class, 'show'])->name('goals.show');
        Route::delete('goals/{goal}', [GoalController::class, 'destroy'])->name('goals.destroy');
        Route::get('goals/{goal}/edit', [GoalController::class, 'edit'])->name('goals.edit');
    });

    // Contracts routes
    Route::middleware('permission:manage_contracts')->group(function () {
        Route::get('contracts', [ContractController::class, 'index'])->name('contracts.index');
        Route::post('contracts', [ContractController::class, 'store'])->name('contracts.store');
        Route::get('contracts/create/{customerId?}', [ContractController::class, 'create'])->name('contracts.create');
        Route::put('contracts/{contract}', [ContractController::class, 'update'])->name('contracts.update');
        Route::get('contracts/{contract}', [ContractController::class, 'show'])->name('contracts.show');
        Route::delete('contracts/{contract}', [ContractController::class, 'destroy'])->name('contracts.destroy');
        Route::get('contracts/{contract}/edit', [ContractController::class, 'edit'])->name('contracts.edit');
        Route::get('contracts/{contract}/{group}', [ContractController::class, 'show']);
        Route::get('contracts-summary',
                [ContractController::class, 'contractSummary'])->name('contracts.contractSummary');
    });

    // Proposals routes
    Route::middleware('permission:manage_proposals')->group(function () {
        Route::get('proposals', [ProposalController::class, 'index'])->name('proposals.index');
        Route::post('proposals', [ProposalController::class, 'store'])->name('proposals.store');
        Route::get('proposals/create/{relatedTo?}', [ProposalController::class, 'create'])->name('proposals.create');
        Route::get('proposals/{proposal}/edit', [ProposalController::class, 'edit'])->name('proposals.edit');
        Route::post('proposals/{proposal}', [ProposalController::class, 'update'])->name('proposals.update');
        Route::delete('proposals/{proposal}', [ProposalController::class, 'destroy'])->name('proposals.destroy');
        Route::get('proposals/{proposal}', [ProposalController::class, 'show'])->name('proposals.show');
        Route::put('proposals/{proposal}/change-status',
                [ProposalController::class, 'changeStatus'])->name('proposal.change-status');
        Route::get('proposals/{proposal}/view-as-customer',
                [ProposalController::class, 'viewAsCustomer'])->name('proposal.view-as-customer');
        Route::get('proposals/{proposal}/pdf', [ProposalController::class, 'convertToPdf'])->name('proposal.pdf');
        Route::post('proposals/{proposal}/convert-to-invoice',
                [ProposalController::class, 'convertToInvoice'])->name('proposal.convert-to-invoice');
        Route::post('proposals/{proposal}/convert-to-estimate',
                [ProposalController::class, 'convertToEstimate'])->name('proposal.convert-to-estimate');
        Route::get('proposals/{proposal}/{group}', [ProposalController::class, 'show']);
    });

    // Credit Notes routes
    Route::middleware('permission:manage_credit_notes')->group(function () {
        Route::get('credit-notes', [CreditNoteController::class, 'index'])->name('credit-notes.index');
        Route::post('credit-notes', [CreditNoteController::class, 'store'])->name('credit-notes.store');
        Route::get('credit-notes/create/{customerId?}', [CreditNoteController::class, 'create'])->name('credit-notes.create');
        Route::get('credit-notes/{creditNote}/edit', [CreditNoteController::class, 'edit'])->name('credit-notes.edit');
        Route::post('credit-notes/{creditNote}', [CreditNoteController::class, 'update'])->name('credit-notes.update');
        Route::delete('credit-notes/{creditNote}', [CreditNoteController::class, 'destroy'])->name('credit-notes.destroy');
        Route::get('credit-notes/{creditNote}', [CreditNoteController::class, 'show'])->name('credit-notes.show');
        Route::put('credit-notes/{creditNote}/change-payment-status',
                [CreditNoteController::class, 'changePaymentStatus'])->name('credit-note.change-payment-status');
        Route::get('credit-notes/{creditNote}/view-as-customer',
                [CreditNoteController::class, 'viewAsCustomer'])->name('credit-note.view-as-customer');
        Route::get('credit-notes/{creditNote}/pdf', [CreditNoteController::class, 'convertToPdf'])->name('credit-note.pdf');
    });

    // setting routes
    Route::middleware('permission:manage_settings')->group(function () {
        Route::get('settings', [SettingController::class, 'show'])->name('settings.show');
        Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
    });

    // Activity Log
    Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity.logs.index');
    Route::post('change-filter', [ActivityLogController::class, 'index'])->name('change.filter');

    Route::get('translation-manager',
            [TranslationManagerController::class, 'index'])->name('translation-manager.index');
    Route::get('translation-manager/{language}/edit',
            [TranslationManagerController::class, 'edit'])->name('translation.manager.edit');
    Route::get('language/translation/{language}',
            [TranslationManagerController::class, 'showTranslation'])->name('language.translation');
    Route::post('translation-manager',
            [TranslationManagerController::class, 'store'])->name('translation-manager.store');
    Route::put('translation-manager/{language}',
            [TranslationManagerController::class, 'update'])->name('translation-manager.update');
    Route::delete('translation-manager/{language}',
            [TranslationManagerController::class, 'destroy'])->name('translation.manager.destroy');
    Route::post('language/translation/{language}/update',
            [TranslationManagerController::class, 'updateTranslation'])->name('language.translation.update');

    // Task routes
    Route::middleware('permission:manage_tasks')->group(function () {
        Route::get('tasks', [TaskController::class, 'index'])->name('tasks.index');
        Route::get('tasks/create/{relatedTo?}/{customerId?}',
                [TaskController::class, 'create'])->name('tasks.create');
        Route::post('tasks', [TaskController::class, 'store'])->name('tasks.store');
        Route::get('tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
        Route::get('tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
        Route::put('tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
        Route::delete('tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
        Route::get('change-owner', [TaskController::class, 'changeOwner'])->name('change-owner');
        Route::put('tasks/{task}/status/{status}', [TaskController::class, 'changeStatus'])->name('tasks.changeStatus');
        Route::get('tasks-kanban-list', [TaskController::class, 'kanbanList'])->name('tasks.kanbanList');
        Route::get('tasks/{task}/comments-count',
                [TaskController::class, 'getCommentsCount'])->name('task.comments-count');
        Route::get('tasks/{task}/{group}', [TaskController::class, 'show']);
    });

    // Projects routes
    Route::middleware('permission:manage_projects')->group(function () {
        Route::get('projects', [ProjectController::class, 'index'])->name('projects.index');
        Route::post('projects', [ProjectController::class, 'store'])->name('projects.store');
        Route::get('projects/create/{customerId?}', [ProjectController::class, 'create'])->name('projects.create');
        Route::put('projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
        Route::get('projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
        Route::delete('projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');
        Route::get('projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
        Route::post('member-as-per-customer',
                [ProjectController::class, 'memberAsPerCustomer'])->name('projects.memberAsPerCustomer');
        Route::get('projects/{project}/{group}', [ProjectController::class, 'show']);
    });

    // Tickets routes
    Route::middleware('permission:manage_tickets')->group(function () {
        Route::get('tickets', [TicketController::class, 'index'])->name('ticket.index');
        Route::get('tickets/create', [TicketController::class, 'create'])->name('ticket.create');
        Route::post('tickets', [TicketController::class, 'store'])->name('ticket.store');
        Route::get('tickets/{ticket}', [TicketController::class, 'show'])->name('ticket.show');
        Route::get('tickets/{ticket}/edit', [TicketController::class, 'edit'])->name('ticket.edit');
        Route::put('tickets/{ticket}', [TicketController::class, 'update'])->name('ticket.update');
        Route::delete('tickets/{ticket}', [TicketController::class, 'destroy'])->name('ticket.destroy');
        Route::get('tickets/predefinedReplyBody/{predefinedReplyId?}',
                [TicketController::class, 'getPredefinedReplyBody'])->name('ticket.reply.body');
        Route::get('tickets-attachment-download/{ticket}', [TicketController::class, 'downloadMedia']);
        Route::get('tickets/{ticket}/{group}', [TicketController::class, 'show'])->name('tickets.show');
        Route::post('tickets/{ticket}/{group}/notes-count',
                [TicketController::class, 'getNotesCount']);
        Route::get('tickets-kanban-list', [TicketController::class, 'kanbanList'])->name('tickets.kanbanList');
        Route::put('tickets/{ticket}/status/{statusId}',
                [TicketController::class, 'changeStatus'])->name('tickets.changeStatus');
        Route::delete('ticket-attachment-delete',
                [TicketController::class, 'attachmentDelete'])->name('ticket.attachment');
        Route::get('download-media/{mediaItem}',
                [TicketController::class, 'download'])->name('ticket.download.media');
    });

    // Ticket Priorities routes
    Route::middleware('permission:manage_ticket_priority')->group(function () {
        Route::get('ticket-priorities', [TicketPriorityController::class, 'index'])->name('ticketPriorities.index');
        Route::post('ticket-priorities', [TicketPriorityController::class, 'store'])->name('ticketPriorities.store');
        Route::get('ticket-priorities/{ticketPriority}/edit',
                [TicketPriorityController::class, 'edit'])->name('ticketPriorities.edit');
        Route::put('ticket-priorities/{ticketPriority}',
                [TicketPriorityController::class, 'update'])->name('ticketPriorities.update');
        Route::delete('ticket-priorities/{ticketPriority}',
                [TicketPriorityController::class, 'destroy'])->name('ticketPriorities.destroy');
        Route::post('ticket-priorities/{ticket_priority_id}/active-deactive',
                [TicketPriorityController::class, 'activeDeActiveCategory'])->name('active.deactive');
    });

    // Ticket Status routes
    Route::middleware('permission:manage_ticket_statuses')->group(function () {
        Route::get('ticket-statuses', [TicketStatusController::class, 'index'])->name('ticket.status.index');
        Route::post('ticket-statuses', [TicketStatusController::class, 'store'])->name('ticket.status.store');
        Route::get('ticket-statuses/{ticketStatus}/edit',
                [TicketStatusController::class, 'edit'])->name('ticket.status.edit');
        Route::put('ticket-statuses/{ticketStatus}',
                [TicketStatusController::class, 'update'])->name('ticket.status.update');
        Route::delete('ticket-statuses/{ticketStatus}',
                [TicketStatusController::class, 'destroy'])->name('ticket.status.destroy');
    });

    // Payment Modes routes
    Route::middleware('permission:manage_payment_mode')->group(function () {
        Route::get('payment-modes', [PaymentModeController::class, 'index'])->name('payment-modes.index');
        Route::post('payment-modes', [PaymentModeController::class, 'store'])->name('payment-modes.store');
        Route::get('payment-modes/{paymentMode}/edit', [PaymentModeController::class, 'edit'])->name('payment-modes.edit');
        Route::put('payment-modes/{paymentMode}', [PaymentModeController::class, 'update'])->name('payment-modes.update');
        Route::delete('payment-modes/{paymentMode}',
                [PaymentModeController::class, 'destroy'])->name('payment-modes.destroy');
        Route::post('payment-modes/{paymentMode}/active-deactive',
                [PaymentModeController::class, 'activeDeActivePaymentMode'])->name('payment-modes.active.deactive');
        Route::get('payment-modes/{paymentMode}',
                [PaymentModeController::class, 'show'])->name('payment-modes.show');
    });

    // Lead Sources route
    Route::middleware('permission:manage_lead_sources')->group(function () {
        Route::get('lead-sources', [LeadSourceController::class, 'index'])->name('lead.source.index');
        Route::post('lead-sources', [LeadSourceController::class, 'store'])->name('lead.source.store');
        Route::get('lead-sources/{leadSource}/edit', [LeadSourceController::class, 'edit'])->name('lead.source.edit');
        Route::put('lead-sources/{leadSource}', [LeadSourceController::class, 'update'])->name('lead.source.update');
        Route::delete('lead-sources/{leadSource}', [LeadSourceController::class, 'destroy'])->name('lead.source.destroy');
    });

    // Lead Status routes
    Route::middleware('permission:manage_lead_status')->group(function () {
        Route::get('lead-status', [LeadStatusController::class, 'index'])->name('lead.status.index');
        Route::post('lead-status', [LeadStatusController::class, 'store'])->name('lead.status.store');
        Route::get('lead-status/{leadStatus}/edit', [LeadStatusController::class, 'edit'])->name('lead.status.edit');
        Route::put('lead-status/{leadStatus}', [LeadStatusController::class, 'update'])->name('lead.status.update');
        Route::delete('lead-status/{leadStatus}', [LeadStatusController::class, 'destroy'])->name('lead.status.destroy');
    });

    // Invoices routes
    Route::middleware('permission:manage_invoices')->group(function () {
        Route::get('invoices', [InvoiceController::class, 'index'])->name('invoices.index');
        Route::post('invoices', [InvoiceController::class, 'store'])->name('invoices.store');
        Route::get('invoices/create/{customerId?}', [InvoiceController::class, 'create'])->name('invoices.create');
        Route::get('invoices/{invoice}/edit', [InvoiceController::class, 'edit'])->name('invoices.edit');
        Route::post('invoices/{invoice}', [InvoiceController::class, 'update'])->name('invoices.update');
        Route::delete('invoices/{invoice}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');
        Route::get('invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
        Route::get('invoices/{invoice}/view-as-customer',
                [InvoiceController::class, 'viewAsCustomer'])->name('invoice.view-as-customer');
        Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'covertToPdf'])->name('invoice.pdf');
        Route::put('invoices/{invoice}/change-status',
                [InvoiceController::class, 'changeStatus'])->name('invoice.change-status');
        Route::get('invoices/{invoice}/{group}', [InvoiceController::class, 'show']);
        Route::post('invoices/{invoice}/{group}/notes-count',
                [InvoiceController::class, 'getNotesCount']);
    });

    Route::get('customer-address', [InvoiceController::class, 'getCustomerAddress'])->name('get.customer.address');
    Route::get('credit-note-customer-address',
            [CreditNoteController::class, 'getCustomerAddress'])->name('get.creditnote.customer.address');
    Route::get('estimates-customer-address',
            [EstimateController::class, 'getCustomerAddress'])->name('get.estimate.customer.address');
    Route::get('proposal-customer-address',
            [ProposalController::class, 'getCustomerAddress'])->name('get.proposal.customer.address');

    // Payments routes
    Route::middleware('permission:manage_payments')->group(function () {
        Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');
        Route::post('payments', [PaymentController::class, 'store'])->name('payments.store');
        Route::delete('payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');
        Route::get('payments/edit', [PaymentController::class, 'addPayment'])->name('payments.create');
    });

    // Payment for Invoices routes
    Route::get('payments-list', [Listing\PaymentListing::class, 'index'])->name('payments.list.index');
    Route::get('payment-details/{payment?}', [Listing\PaymentListing::class, 'show'])->name('payments.list.show');

    // Estimates routes
    Route::middleware('permission:manage_estimates')->group(function () {
        Route::get('estimates', [EstimateController::class, 'index'])->name('estimates.index');
        Route::get('estimates/create/{customerId?}', [EstimateController::class, 'create'])->name('estimates.create');
        Route::post('estimates', [EstimateController::class, 'store'])->name('estimates.store');
        Route::get('estimates/{estimate}/edit', [EstimateController::class, 'edit'])->name('estimates.edit');
        Route::post('estimates/{estimate}', [EstimateController::class, 'update'])->name('estimates.update');
        Route::delete('estimates/{estimate}', [EstimateController::class, 'destroy'])->name('estimates.destroy');
        Route::get('estimates/{estimate}', [EstimateController::class, 'show'])->name('estimates.show');
        Route::put('estimates/{estimate}/change-status',
                [EstimateController::class, 'changeStatus'])->name('estimate.change-status');
        Route::get('estimates/{estimate}/view-as-customer',
                [EstimateController::class, 'viewAsCustomer'])->name('estimate.view-as-customer');
        Route::get('estimates/{estimate}/pdf', [EstimateController::class, 'convertToPdf'])->name('estimate.pdf');
        Route::post('estimates/{estimate}/convert-to-invoice',
                [EstimateController::class, 'convertToInvoice'])->name('estimate.convert-to-invoice');
        Route::get('estimates/{estimate}/{group}', [EstimateController::class, 'show']);
    });

    // Profile routes
    Route::post('change-password', [UserController::class, 'changePassword'])->name('change.password');
    Route::get('profile', [UserController::class, 'editProfile'])->name('profile');
    Route::post('update-profile', [UserController::class, 'updateProfile'])->name('update.profile');
    Route::post('change-language', [UserController::class, 'changeLanguage'])->name('change.language');

    Route::post('contract-month-filter', [DashboardController::class, 'contractMonthFilter'])->name('contract.month.filter');

    // Country module routes
    Route::get('countries', [CountryController::class, 'index'])->name('countries.index');
    Route::post('countries', [CountryController::class, 'store'])->name('countries.store');
    Route::get('countries/{country}/edit', [CountryController::class, 'edit'])->name('countries.edit');
    Route::put('countries/{country}', [CountryController::class, 'update'])->name('countries.update');
    Route::delete('countries/{country}', [CountryController::class, 'destroy'])->name('countries.destroy');
    Route::get('countries/{country}', [CountryController::class, 'show'])->name('countries.show');
});

Route::middleware(['auth', 'xss', 'checkUserStatus', 'checkRoleAdmin', 'role:client'])->prefix('client')->group(function () {
    Route::get('dashboard', [Clients\DashboardController::class, 'index'])->name('clients.dashboard');

    // Projects routes
    Route::middleware('permission:contact_projects')->group(function () {
        Route::get('projects', [Clients\ProjectController::class, 'index'])->name('clients.projects.index');
        Route::get('projects/{project}', [Clients\ProjectController::class, 'show'])->name('clients.projects.show');
        Route::get('projects/{project}/{group}',
            [Clients\ProjectController::class, 'show']);
    });

    // Tasks routes
    Route::get('tasks', [Clients\TaskController::class, 'index'])->name('clients.tasks.index');
    Route::get('tasks/{task}', [Clients\TaskController::class, 'show'])->name('clients.tasks.show');
    Route::get('tasks/{task}/{group}', [Clients\TaskController::class, 'show']);

    // Reminder routes
    Route::get('reminder', [Clients\ReminderController::class, 'index'])->name('clients.reminder.index');

    // Invoices routes
    Route::middleware('permission:contact_invoices')->group(function () {
        Route::get('invoices', [Clients\InvoiceController::class, 'index'])->name('clients.invoices.index');
        Route::get('invoices/{invoice}/view-as-customer',
            [Clients\InvoiceController::class, 'viewAsCustomer'])->name('clients.invoices.view-as-customer');
        Route::get('invoices/{invoice}/pdf',
            [Clients\InvoiceController::class, 'covertToPdf'])->name('clients.invoice.pdf');
        Route::post('invoice-stripe-payment', [PaymentController::class, 'createSession']);
        Route::get('invoice-payment-success',
            [PaymentController::class, 'paymentSuccess'])->name('clients.invoice-payment-success');
        Route::get('invoice-failed-payment',
            [PaymentController::class, 'handleFailedPayment'])->name('clients.invoice-failed-payment');
    });

    // Proposals routes
    Route::middleware('permission:contact_proposals')->group(function () {
        Route::get('proposals', [Clients\ProposalController::class, 'index'])->name('clients.proposals.index');
        Route::get('proposals/{proposal}/view-as-customer',
            [Clients\ProposalController::class, 'viewAsCustomer'])->name('clients.proposals.view-as-customer');
        Route::post('proposals/{proposal}/change-status',
            [Clients\ProposalController::class, 'changeStatus'])->name('clients.proposals.change-status');
        Route::get('proposals/{proposal}/pdf', [Clients\ProposalController::class, 'covertToPdf'])->name('clients.proposal.pdf');
    });

    // Contracts routes
    Route::middleware('permission:contact_contracts')->group(function () {
        Route::get('contracts', [Clients\ContractController::class, 'index'])->name('clients.contracts.index');
        Route::get('contracts/{contract}/view-as-customer', [Clients\ContractController::class, 'viewAsCustomer'])
            ->name('clients.contracts.view-as-customer');
        Route::get('contracts/{contract}/pdf',
            [Clients\ContractController::class, 'convertToPdf'])->name('clients.contracts.pdf');
        Route::get('contracts-summary',
            [Clients\ContractController::class, 'contractSummary'])->name('contracts.contract-summary');
    });

    // Estimates routes
    Route::middleware('permission:contact_estimates')->group(function () {
        Route::get('estimates', [Clients\EstimateController::class, 'index'])->name('clients.estimates.index');
        Route::get('estimates/{estimate}/view-as-customer', [Clients\EstimateController::class, 'viewAsCustomer'])
            ->name('clients.estimates.view-as-customer');
        Route::get('estimates/{estimate}/pdf', [Clients\EstimateController::class, 'convertToPDF'])->name('clients.estimate.pdf');
        Route::post('estimates/{estimate}/change-status', [Clients\EstimateController::class, 'changeStatus'])
            ->name('clients.estimates.change-status');
    });

    // Announcements routes
    Route::get('announcements', [Clients\AnnouncementController::class, 'index'])->name('clients.announcements.index');
    Route::get('announcements/{announcement}',
        [Clients\AnnouncementController::class, 'show'])->name('clients.announcements.show');

    // Company Details Routes
    Route::get('company-details',
        [Clients\CompanyController::class, 'companyDetails'])->name('clients.company-details');
    Route::put('company-details/{customer}', [Clients\CompanyController::class, 'update'])->name('clients.update');

    // Profile routes
    Route::post('change-password', [Clients\UserController::class, 'changePassword'])->name('clients.change.password');
    Route::get('profile', [Clients\UserController::class, 'editProfile'])->name('clients.profile');
    Route::post('update-profile', [Clients\UserController::class, 'updateProfile'])->name('clients.update.profile');
    Route::post('change-language', [Clients\UserController::class, 'changeLanguage'])->name('clients.change.language');

    //Header Client Notification
    Route::get('get-notifications',
        [Clients\DashboardController::class, 'getNotifications'])->name('client.notifications.index');
    Route::post('notification/{notification}/read',
        [Clients\DashboardController::class, 'readNotification'])->name('client.notifications.read');
    Route::post('read-all-notification',
        [Clients\DashboardController::class, 'readAllNotification'])->name('client.notifications.read.all');

    // Ticket routes
    Route::get('tickets', [Clients\TicketController::class, 'index'])->name('client.tickets.index');
    Route::get('tickets/create', [Clients\TicketController::class, 'create'])->name('client.tickets.create');
    Route::post('tickets', [Clients\TicketController::class, 'store'])->name('client.tickets.store');
    Route::get('tickets/{ticket}', [Clients\TicketController::class, 'show'])->name('client.tickets.show');
    Route::delete('tickets/{ticket}', [Clients\TicketController::class, 'destroy'])->name('client.tickets.destroy');
});

Route::middleware(['auth', 'xss', 'checkUserStatus'])->group(function () {
    // ticket reply routes
    Route::post('ticket-reply', [TicketReplyController::class, 'store'])->name('ticket.reply.store');
    Route::get('ticket-reply/{ticket}/edit', [TicketReplyController::class, 'edit'])->name('ticket.reply.edit');
    Route::put('ticket-reply/{ticket}', [TicketReplyController::class, 'update'])->name('ticket.reply.update');
    Route::delete('ticket-reply/{ticket}', [TicketReplyController::class, 'destroy'])->name('ticket.reply.destroy');
});

Route::get('article-search', function () {
    return view('articles.search');
});

require __DIR__.'/upgrade.php';
