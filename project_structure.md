## NASS-SPLIT Project Structure

### App Layer
```
app/
├── ApiResponses.php
├── Events/
│   └── MessageSent.php
├── Http/
│   ├── Controllers/
│   │   ├── Controller.php
│   │   ├── Api/
│   │   │   ├── Auth/
│   │   │   │   ├── AuthenticatedSessionController.php
│   │   │   │   ├── EmailVerificationNotificationController.php
│   │   │   │   ├── NewPasswordController.php
│   │   │   │   ├── PasswordResetLinkController.php
│   │   │   │   ├── RegisteredUserController.php
│   │   │   │   ├── SocialAuthController.php
│   │   │   │   └── VerifyEmailController.php
│   │   │   └── V1/
│   │   │       ├── AdminController.php
│   │   │       ├── CategoryController.php
│   │   │       ├── ConversationController.php
│   │   │       ├── ExpenseController.php
│   │   │       ├── GroupController.php
│   │   │       ├── InvitationController.php
│   │   │       ├── MembershipController.php
│   │   │       ├── MessageController.php
│   │   │       ├── OwnerController.php
│   │   │       ├── PaymentController.php
│   │   │       ├── ProfileController.php
│   │   │       └── UserController.php
│   ├── Middleware/
│   │   ├── CheckIfBanned.php
│   │   └── EnsureEmailIsVerified.php
│   ├── Requests/
│   │   ├── Api/
│   │   │   ├── LoginUserRequest.php
│   │   │   ├── SignUpRequest.php
│   │   │   └── V1/
│   │   │       ├── CategoryFormRequest.php
│   │   │       ├── ExpenseFormRequest.php
│   │   │       ├── GroupFormRequest.php
│   │   │       ├── MessageRequest.php
│   │   │       ├── PaymentRequest.php
│   │   │       └── UpdateProfileRequest.php
│   │   ├── Auth/
│   │   │   └── LoginRequest.php
│   │   ├── LoginRequest.php
│   │   └── RegisterFormRequest.php
│   └── Resources/V1/
│       ├── ExpenseResource.php
│       └── UserResource.php
├── Mail/
│   └── GroupInvitationMail.php
├── Models/
│   ├── Admin.php
│   ├── Attachment.php
│   ├── Category.php
│   ├── Conversation.php
│   ├── Expense.php
│   ├── Group.php
│   ├── Invitation.php
│   ├── Membership.php
│   ├── Message.php
│   ├── Payment.php
│   ├── Split.php
│   └── User.php
├── Notifications/
│   └── NewMessageSentNotification.php
├── Providers/
│   └── AppServiceProvider.php
└── Services/
    ├── GroupService.php
    ├── MembershipService.php
    ├── SettlementService.php
    ├── StrategyManagerService.php
    └── Strategies/
        ├── SplitStrategy.php
        ├── EqualSplit.php
        ├── FixedSplit.php
        └── PercentageSplit.php
```

### Routes
```
routes/
├── api.php          (auth: login, register, password reset, social, email verify)
├── api_v1.php       (all V1 API endpoints)
├── auth.php
├── channels.php
├── console.php
└── web.php
```

### Database
```
database/
├── factories/
│   └── UserFactory.php
├── migrations/
│   ├── users, cache, jobs
│   ├── groups
│   ├── memberships
│   ├── personal_access_tokens
│   ├── payments
│   ├── invitations
│   ├── categories
│   ├── expenses
│   ├── attachments
│   ├── conversations
│   ├── messages
│   ├── splits
│   ├── admins
│   └── notifications
└── seeders/
    ├── DatabaseSeeder.php
    └── UserSeeder.php
```

### Resources (Blade/Frontend)
```
resources/
├── fonts/           (Blue_Cashews, Cashmero, Heavitas)
├── js/
│   └── echo.js
└── views/
    ├── auth/        (login, register, checkMail, forgetPassword, resetPasswordForm)
    ├── components/  (brand, button, card, heading, icon-button, link, navbar, form/*)
    ├── emails/      (group-invitation)
    ├── group/       (createGroup, myGroups)
    ├── layouts/     (app, guest)
    └── globalDashboard.blade.php
```

### Tests
```
tests/
├── TestCase.php
├── Feature/
│   ├── ExampleTest.php
│   └── Auth/ (Authentication, EmailVerification, PasswordReset, Registration)
└── Unit/
    └── ExampleTest.php
```