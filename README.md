# ANS Realty - Real Estate CRM & Lead Management System

<p align="center">
  <strong>Built with Laravel 11 · FilamentPHP 3 · MySQL</strong>
</p>

<p align="center">
  Complete CRM solution for real estate businesses to track leads from first inquiry → opportunity → closure → post-sale follow-up
</p>

<p align="center">
  <strong>🎉 Project Status: 80% Complete - Phase 2D Ready</strong>
</p>

---

## 📊 Current Status

**✅ Completed:**
- Database Schema (27+ tables)
- 8 Admin Resources with full CRUD
- 8 Relation Managers
- 5 Dashboard Widgets (code ready)
- Public Homepage
- Comprehensive Documentation

**⏳ Next Steps:**
1. Create dashboard widget files (15-30 min) - [See Guide](#dashboard-widgets)
2. Build property listing page (1-2 hours)
3. Build property detail page (1 hour)

**Progress:** [View Detailed Progress Tracker](PROJECT-PROGRESS.md)

---

## 🚀 Quick Start

```bash
# 1. Clone and setup
git clone <repository-url>
cd ansrealty
composer install

# 2. Configure environment
cp .env.example .env
php artisan key:generate

# 3. Setup database (update .env with your DB credentials)
php artisan migrate
php artisan db:seed

# 4. Create admin user
php artisan shield:super-admin

# 5. Start development
php artisan serve
```

Visit: `http://localhost:8000/admin`

---

## 📋 Features Overview

### ✅ **Lead Management**
- Multi-source lead capture (Website, Facebook, Google, WhatsApp, Walk-in, Referral)
- Lead lifecycle tracking (New → Contacted → Qualified → Converted)
- Priority tagging (Hot, Warm, Cold)
- Automated assignment to agents
- UTM parameter tracking

### ✅ **Opportunity Pipeline**
- Visual Kanban board with 12 stages
- Deal probability tracking (0-100%)
- Expected vs actual closure dates
- Lost reason analysis
- Multiple properties per opportunity

### ✅ **Property Inventory**
- Comprehensive property details (20+ fields)
- Builder/Developer management
- RERA number tracking
- Image gallery & floor plans
- Availability status tracking

### ✅ **Site Visit Management**
- Schedule & track visits
- Customer feedback & ratings
- Agent notes
- Automatic follow-up reminders

### ✅ **Task & Follow-up System**
- Call, Email, Meeting, WhatsApp tasks
- Priority & status tracking
- Overdue alerts
- Daily task dashboard

### ✅ **Negotiation Tracking**
- Offer & counter-offer history
- Discount approval workflow
- Booking amount tracking
- Payment milestones

### ✅ **Commission Management**
- Automatic calculation
- Agent split tracking
- Approval workflow
- Payment tracking

### ✅ **Post-Sales Tracking**
- Agreement execution
- Loan processing status
- Registration tracking
- Handover management
- Customer satisfaction surveys

---

## 🗂️ Tech Stack

- **Backend:** Laravel 11
- **Admin Panel:** FilamentPHP 3.2
- **Database:** MySQL
- **Authentication:** Filament Auth + Shield (RBAC)
- **Activity Logging:** Spatie Activity Log
- **Import/Export:** Laravel Excel
- **File Management:** Spatie Media Library

---

## 📚 Documentation

### Setup & Getting Started
- **[Complete Setup Summary](COMPLETE-SETUP-SUMMARY.md)** - What's built & how to use it
- **[Project Progress](PROJECT-PROGRESS.md)** - Detailed progress tracker (80% complete)
- **[Implementation Guide](IMPLEMENTATION-GUIDE.md)** - Complete setup & development roadmap
- **[Database Schema](DATABASE-SCHEMA.md)** - Visual ER diagram & relationships
- **[Command Reference](COMMANDS.md)** - Common commands & troubleshooting

### Phase Documentation
- **[Phase 1 Summary](PHASE-1-SUMMARY.md)** - Database setup
- **[Phase 2A Summary](PHASE-2A-SUMMARY.md)** - Basic resources
- **[Phase 2B Complete](PHASE-2B-COMPLETE.md)** - Advanced resources
- **[Phase 2C Complete](PHASE-2C-COMPLETE.md)** - Relation managers
- **[Phase 2D Widgets Guide](PHASE-2D-WIDGETS-GUIDE.md)** - Dashboard widgets (ready for setup)
- **[Phase 2D Complete](PHASE-2D-COMPLETE.md)** - Widgets summary

### Quick Start Guides
- **[START HERE - Widgets](START-HERE-WIDGETS.md)** - Create dashboard widgets (15 min)
- **[Frontend Guide](FRONTEND-GUIDE.md)** - Public website development
- **[Testing Guide](TESTING-GUIDE.md)** - Testing procedures

---

## 🏗️ Database Schema (13 Tables)

1. **lead_sources** - Marketing channels
2. **lead_statuses** - Lead lifecycle stages
3. **opportunity_stages** - Deal pipeline stages
4. **builders** - Property developers
5. **properties** - Property inventory
6. **leads** - Lead management
7. **opportunities** - Deal tracking
8. **opportunity_property** - Property-opportunity linking
9. **site_visits** - Visit scheduling
10. **tasks** - Follow-ups & activities
11. **negotiations** - Price discussions
12. **commissions** - Agent commissions
13. **post_sales** - Post-closure tracking

---

## 👥 User Roles

- **Super Admin** - Full system access
- **Sales Manager** - Team management & reports
- **Sales Agent** - Own leads & opportunities
- **Telecaller** - Lead capture & initial contact
- **Accounts** - Commission & finance (read-only)
- **Marketing** - Lead campaigns & sources

---

## 🎯 Project Status & Implementation

### ✅ Phase 1: Database Schema (COMPLETED)
- [x] 27+ database tables
- [x] All relationships defined
- [x] Master data seeders
- [x] Test data generation

### ✅ Phase 2A: Basic Resources (COMPLETED)
- [x] LeadResource - Lead management
- [x] OpportunityResource - Pipeline tracking
- [x] PropertyResource - Inventory with media
- [x] BuilderResource - Developer management
- [x] SiteVisitResource - Visit scheduling
- [x] TaskResource - Polymorphic task tracking

### ✅ Phase 2B: Advanced Resources (COMPLETED)
- [x] CommissionResource - Auto-calculating commissions
- [x] NegotiationResource - Price negotiation tracking

### ✅ Phase 2C: Relation Managers (COMPLETED)
- [x] 3 Lead relation managers (Opportunities, Tasks, Site Visits)
- [x] 5 Opportunity relation managers (Properties, Site Visits, Tasks, Negotiations, Commissions)

### 🔄 Phase 2D: Dashboard Widgets (READY FOR SETUP)
- [x] Widget code written (590+ lines)
- [x] Complete documentation
- [x] Automated setup script
- [ ] **ACTION REQUIRED:** Create 5 widget files (15-30 min)
  - LeadStatsOverview (4 stat cards)
  - OpportunityPipeline (bar chart)
  - TasksDueWidget (table with actions)
  - RevenueWidget (4 stat cards)
  - SiteVisitWidget (table with actions)

**📖 See:** [START-HERE-WIDGETS.md](START-HERE-WIDGETS.md) for quick setup

### 🔄 Phase 3: Public Website (20% COMPLETE)
- [x] Homepage design & layout
- [ ] Property listing page
- [ ] Property detail page
- [ ] Contact form
- [ ] Lead auto-capture

### ⏳ Phase 4: Features & Integrations (PENDING)
- [ ] Email notifications
- [ ] WhatsApp integration
- [ ] Reports & analytics
- [ ] Role-based permissions
- [ ] API development

---

## 🎉 What's Working Now

### Admin Panel (http://localhost:8000/admin)
✅ Complete CRM backend with:
- 8 fully functional resources
- 8 relation managers
- Navigation badges (live counts)
- Media library integration
- Advanced filters & search
- Quick actions & bulk operations
- Status tracking & workflows

### Public Website (http://localhost:8000)
✅ Modern homepage with:
- Hero section with search bar
- Property type cards
- Feature highlights
- WhatsApp integration
- Responsive design

### Database
✅ Complete schema with:
- 27+ tables
- 40+ relationships
- Sample data seeded
- Optimized indexes

---

## 🚀 Next Immediate Steps

### 1. Create Dashboard Widgets (15-30 min)
Run these commands or use `create-widgets.bat`:
```bash
php artisan make:filament-widget LeadStatsOverview --stats-overview
php artisan make:filament-widget OpportunityPipeline --chart
php artisan make:filament-widget TasksDueWidget --table
php artisan make:filament-widget RevenueWidget --stats-overview
php artisan make:filament-widget SiteVisitWidget --table
```
Then copy code from **[PHASE-2D-WIDGETS-GUIDE.md](PHASE-2D-WIDGETS-GUIDE.md)**

### 2. Build Property Listing Page (1-2 hours)
- Create route & controller
- Design grid layout
- Add filters (Type, City, Budget)
- Add pagination

### 3. Build Property Detail Page (1 hour)
- Create detail view
- Add image gallery
- Add inquiry form
- Link to lead capture

**📊 Track Progress:** [PROJECT-PROGRESS.md](PROJECT-PROGRESS.md)
- [ ] Policies & Permissions

### 📅 Phase 3: Dashboards & Widgets (UPCOMING)
- [ ] Sales dashboard
- [ ] Agent performance metrics
- [ ] Conversion funnel
- [ ] Revenue analytics

### 📅 Phase 4: Automation (UPCOMING)
- [ ] Events & Listeners
- [ ] Email notifications
- [ ] Task automation
- [ ] Stage change triggers

### 📅 Phase 5: Advanced Features (UPCOMING)
- [ ] CSV Import/Export
- [ ] API endpoints
- [ ] Webhook receivers
- [ ] Audit logs

---

## 🔧 Development

```bash
# Run migrations
php artisan migrate

# Fresh start (WARNING: Deletes all data)
php artisan migrate:fresh --seed

# Generate permissions
php artisan shield:generate --all

# Clear cache
php artisan optimize:clear
```

---

## 🆘 Support

For issues, questions, or contributions, please refer to:
- [Implementation Guide](IMPLEMENTATION-GUIDE.md)
- [Command Reference](COMMANDS.md)

---

## 📝 License

This project is proprietary software developed for ANS Realty.

---

<p align="center">
  <em>Built with ❤️ using Laravel & FilamentPHP</em>
</p>
