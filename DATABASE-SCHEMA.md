# Database Schema Visual Reference

## 📊 Complete Entity Relationship Diagram

```
┌─────────────────┐
│     USERS       │
│  (Filament)     │
│─────────────────│
│ • id            │
│ • name          │
│ • email         │
│ • role          │
└────────┬────────┘
         │
         │ assigned_to
         │
    ┌────▼─────────────┐         ┌──────────────────┐
    │   LEAD_SOURCES   │         │  LEAD_STATUSES   │
    │─────────────────│          │──────────────────│
    │ • id            │          │ • id             │
    │ • name          │          │ • name           │
    │ • slug          │          │ • slug           │
    │ • color         │          │ • color          │
    └────────┬────────┘          └────────┬─────────┘
             │                            │
             │ source_id                  │ status_id
             │                            │
        ┌────▼────────────────────────────▼────┐
        │            LEADS                     │
        │──────────────────────────────────────│
        │ • id                                 │
        │ • full_name                          │
        │ • mobile (PRIMARY IDENTIFIER)        │
        │ • email                              │
        │ • budget_min / budget_max            │
        │ • preferred_locations (JSON)         │
        │ • property_types (JSON)              │
        │ • purchase_intent (Buy/Rent/Invest)  │
        │ • lead_source_id → LEAD_SOURCES      │
        │ • lead_status_id → LEAD_STATUSES     │
        │ • assigned_to → USERS                │
        │ • priority (Hot/Warm/Cold)           │
        │ • utm_* fields (5 columns)           │
        │ • first_contact_at                   │
        │ • qualified_at                       │
        │ • converted_at                       │
        └────────┬─────────────────────────────┘
                 │
                 │ lead_id
                 │
        ┌────────▼──────────────────────────────┐
        │       OPPORTUNITIES                   │
        │───────────────────────────────────────│
        │ • id                                  │
        │ • opportunity_number (UNIQUE)         │
        │ • lead_id → LEADS                     │
        │ • assigned_to → USERS                 │
        │ • opportunity_stage_id                │
        │ • title                               │
        │ • expected_value                      │
        │ • probability (0-100%)                │
        │ • expected_close_date                 │
        │ • final_value                         │
        │ • close_status (Open/Won/Lost)        │
        │ • lost_reason                         │
        └────┬────────────────┬─────────────────┘
             │                │
             │                │
    ┌────────▼────┐    ┌──────▼──────────────────┐
    │OPPORTUNITY_ │    │   OPPORTUNITY_STAGES    │
    │  PROPERTY   │    │─────────────────────────│
    │  (PIVOT)    │    │ • id                    │
    │─────────────│    │ • name                  │
    │• opp_id     │    │ • slug                  │
    │• prop_id    │    │ • color                 │
    │• shortlisted│    │ • probability           │
    └──────┬──────┘    │ • order                 │
           │           └─────────────────────────┘
           │
    ┌──────▼──────────────────────────────────┐
    │           PROPERTIES                    │
    │─────────────────────────────────────────│
    │ • id                                    │
    │ • name                                  │
    │ • builder_id → BUILDERS                 │
    │ • project_name                          │
    │ • location, city, state, pincode        │
    │ • rera_number                           │
    │ • property_type (Flat/Villa/Plot...)    │
    │ • listing_type (Sale/Rent/Lease)        │
    │ • carpet_area / built_up_area           │
    │ • bedrooms, bathrooms, parking          │
    │ • floor_number / total_floors           │
    │ • price_min / price_max                 │
    │ • amenities (JSON)                      │
    │ • possession_date / status              │
    │ • availability_status                   │
    │ • is_featured / is_active               │
    │ • description                           │
    └───────┬─────────────────────────────────┘
            │
            │ builder_id
            │
    ┌───────▼────────────────┐
    │      BUILDERS          │
    │────────────────────────│
    │ • id                   │
    │ • name                 │
    │ • company_name         │
    │ • email, phone         │
    │ • rera_number          │
    │ • website              │
    │ • description          │
    └────────────────────────┘


┌─────────────────────────────────────────┐
│          SITE_VISITS                    │
│─────────────────────────────────────────│
│ • id                                    │
│ • lead_id → LEADS                       │
│ • opportunity_id → OPPORTUNITIES        │
│ • property_id → PROPERTIES              │
│ • assigned_to → USERS                   │
│ • scheduled_at                          │
│ • completed_at                          │
│ • status (Planned/Completed/Cancelled)  │
│ • customer_feedback                     │
│ • customer_rating (1-5)                 │
│ • agent_notes                           │
│ • follow_up_required                    │
│ • follow_up_date                        │
└─────────────────────────────────────────┘


┌─────────────────────────────────────────┐
│           TASKS (Polymorphic)           │
│─────────────────────────────────────────│
│ • id                                    │
│ • title                                 │
│ • description                           │
│ • taskable_type (Lead/Opportunity)      │
│ • taskable_id                           │
│ • type (Call/Email/Meeting/WhatsApp)    │
│ • priority (Low/Medium/High/Urgent)     │
│ • status (Pending/Completed/Cancelled)  │
│ • assigned_to → USERS                   │
│ • created_by → USERS                    │
│ • due_date                              │
│ • completed_at                          │
│ • completion_notes                      │
└─────────────────────────────────────────┘


┌─────────────────────────────────────────┐
│          NEGOTIATIONS                   │
│─────────────────────────────────────────│
│ • id                                    │
│ • opportunity_id → OPPORTUNITIES        │
│ • property_id → PROPERTIES              │
│ • listed_price                          │
│ • offered_price                         │
│ • counter_offer_price                   │
│ • final_agreed_price                    │
│ • discount_amount / percentage          │
│ • discount_approved (bool)              │
│ • approved_by → USERS                   │
│ • booking_amount / booking_date         │
│ • terms, notes                          │
└─────────────────────────────────────────┘


┌─────────────────────────────────────────┐
│          COMMISSIONS                    │
│─────────────────────────────────────────│
│ • id                                    │
│ • opportunity_id → OPPORTUNITIES        │
│ • property_id → PROPERTIES              │
│ • agent_id → USERS                      │
│ • deal_value                            │
│ • commission_percentage                 │
│ • gross_commission                      │
│ • split_percentage                      │
│ • net_commission                        │
│ • status (Pending/Approved/Paid)        │
│ • approved_by → USERS                   │
│ • payment_date / reference              │
└─────────────────────────────────────────┘


┌─────────────────────────────────────────┐
│          POST_SALES                     │
│─────────────────────────────────────────│
│ • id                                    │
│ • opportunity_id → OPPORTUNITIES        │
│ • property_id → PROPERTIES              │
│ • customer_id → USERS                   │
│ • agreement_date / number / value       │
│ • registration_date / number            │
│ • loan_required (bool)                  │
│ • bank_name / loan_amount               │
│ • loan_status                           │
│ • possession_date / handover_date       │
│ • customer_satisfaction_rating (1-5)    │
│ • customer_feedback                     │
└─────────────────────────────────────────┘
```

## 🔗 Relationship Summary

### One-to-Many Relationships:
- `users` → `leads` (assigned_to)
- `users` → `opportunities` (assigned_to)
- `users` → `tasks` (assigned_to, created_by)
- `lead_sources` → `leads`
- `lead_statuses` → `leads`
- `opportunity_stages` → `opportunities`
- `builders` → `properties`
- `leads` → `opportunities`
- `opportunities` → `site_visits`
- `opportunities` → `negotiations`
- `opportunities` → `commissions`
- `opportunities` → `post_sales`
- `properties` → `site_visits`
- `properties` → `negotiations`

### Many-to-Many Relationships:
- `opportunities` ↔ `properties` (via `opportunity_property`)

### Polymorphic Relationships:
- `tasks` → `taskable` (Lead or Opportunity)

---

## 📈 Lead Lifecycle Flow

```
1. CAPTURE
   ├─ Website Form
   ├─ Facebook/Google Ads
   ├─ WhatsApp Inquiry
   ├─ Walk-in
   └─ Referral
         ↓
2. LEAD (New)
   ├─ Assign to Agent
   ├─ Create Task (First Call)
   └─ Status: New
         ↓
3. CONTACTED
   ├─ Agent calls customer
   ├─ Update status
   └─ Create follow-up task
         ↓
4. QUALIFIED
   ├─ Budget confirmed
   ├─ Requirements clear
   └─ Shortlist properties
         ↓
5. SITE VISIT PLANNED
   ├─ Schedule visit
   ├─ Assign property
   └─ Send confirmation
         ↓
6. SITE VISIT DONE
   ├─ Collect feedback
   ├─ Rating (1-5)
   └─ Next steps
         ↓
7. CONVERT TO OPPORTUNITY
   ├─ Create opportunity record
   ├─ Link properties
   ├─ Set expected value
   └─ Initial stage: "Requirement Finalized"
         ↓
8. OPPORTUNITY PIPELINE
   ├─ Property Shortlisted (30%)
   ├─ Site Visit Scheduled (40%)
   ├─ Site Visit Completed (50%)
   ├─ Price Discussion (60%)
   ├─ Negotiation (70%)
   ├─ Token Paid (80%)
   ├─ Agreement Stage (90%)
   └─ Registration Stage (95%)
         ↓
9. CLOSED WON (100%)
   ├─ Create commission record
   ├─ Create post-sales record
   ├─ Send thank you email
   └─ Schedule handover
         ↓
10. POST-SALES TRACKING
    ├─ Agreement execution
    ├─ Loan processing
    ├─ Registration
    ├─ Possession
    └─ Customer satisfaction survey
```

---

## 🎨 UI Preview (What Filament Will Generate)

### Lead List Page:
```
┌─────────────────────────────────────────────────────────┐
│ 🔍 Search: [___________]  📊 Filters: [Source▼][Status▼]│
├─────────────────────────────────────────────────────────┤
│ Name          | Mobile      | Source   | Status | Agent │
│───────────────┼─────────────┼──────────┼────────┼───────│
│ John Doe      | 9876543210  | Website  | New 🔵 | Raj   │
│ Jane Smith    | 9123456780  | Facebook | Hot 🔥 | Priya │
│ Mike Johnson  | 9988776655  | Referral | Warm🌤 | Amit  │
└─────────────────────────────────────────────────────────┘
```

### Opportunity Kanban Board:
```
┌─────────────┬─────────────┬─────────────┬─────────────┐
│ Requirement │ Shortlisted │ Negotiation │ Token Paid  │
│   (20%)     │   (30%)     │   (70%)     │   (80%)     │
├─────────────┼─────────────┼─────────────┼─────────────┤
│ ┌─────────┐ │ ┌─────────┐ │ ┌─────────┐ │ ┌─────────┐ │
│ │Deal #123│ │ │Deal #456│ │ │Deal #789│ │ │Deal #111│ │
│ │₹45L     │ │ │₹60L     │ │ │₹80L     │ │ │₹55L     │ │
│ │Raj Kumar│ │ │Priya    │ │ │Amit     │ │ │Raj      │ │
│ └─────────┘ │ └─────────┘ │ └─────────┘ │ └─────────┘ │
└─────────────┴─────────────┴─────────────┴─────────────┘
```

---

## 🔢 Sample Data Sizes

**After seeding:**
- ✅ 10 Lead Sources
- ✅ 9 Lead Statuses
- ✅ 12 Opportunity Stages
- ⏳ 1 Test User (you need to create admin user)

**Production estimates (1 year):**
- ~5,000-10,000 Leads
- ~500-1,000 Opportunities
- ~100-200 Properties
- ~2,000-5,000 Tasks
- ~300-500 Site Visits
- ~100-200 Closures

---

_Generated by GitHub Copilot CLI - Visual Reference_
