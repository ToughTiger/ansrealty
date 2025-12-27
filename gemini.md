Real Estate CRM & Lead Management System

Tech Stack: Laravel 11 · FilamentPHP · MySQL
Goal: Track every lead from first inquiry → opportunity → closure → post-sale follow-up

1️⃣ Core System & Architecture
1.1 Authentication & Authorization

 User login / logout (Filament Auth)

 Role-based access control (Admin, Manager, Agent, Telecaller)

 Permission-based modules (Spatie Permission)

 Multi-user activity tracking (who did what, when)

1.2 User Roles

 Super Admin (Full access)

 Sales Manager

 Sales Agent / Broker

 Telecaller

 Accounts / Finance (Read-only sales + commission)

 Marketing (Leads + campaigns only)

2️⃣ Lead Management (Top of Funnel)
2.1 Lead Capture Sources

 Website contact form

 Facebook Ads leads

 Google Ads leads

 WhatsApp inquiries

 Walk-in customers

 Referral leads

 CSV / Excel bulk upload

 Manual lead entry (Filament form)

2.2 Lead Data Model

 Full Name

 Mobile number (primary identifier)

 Email

 Budget range

 Preferred location(s)

 Property type (Flat, Villa, Plot, Commercial)

 Purchase intent (Buy / Rent / Invest)

 Lead source

 Assigned agent

 Lead status

 Lead priority (Hot / Warm / Cold)

 Notes & remarks

2.3 Lead Status Lifecycle
New → Contacted → Qualified → Site Visit Planned → Site Visit Done → Opportunity → Negotiation → Closed Won / Closed Lost

3️⃣ Opportunity Management (Mid Funnel)
3.1 Opportunity Creation

 Convert qualified lead into opportunity

 Auto-link opportunity to lead

 Assign sales agent

 Expected deal value

 Probability percentage

 Expected closure date

3.2 Opportunity Stages

 Opportunity Created

 Requirement Finalized

 Property Shortlisted

 Site Visit Scheduled

 Site Visit Completed

 Price Discussion

 Negotiation

 Token Amount Paid

 Agreement Stage

 Registration Stage

 Closed Won

 Closed Lost (with reason)

4️⃣ Property Inventory Management
4.1 Property Master

 Property name

 Builder / Developer

 Project location

 RERA number

 Property type

 Carpet / Built-up area

 Price range

 Possession date

 Amenities

 Floor plans upload

 Gallery images

 Availability status

4.2 Property Assignment

 Link properties to opportunities

 Multiple properties per lead

 Mark shortlisted properties

 Track site visits per property

5️⃣ Site Visit Management

 Schedule site visit

 Assign agent

 Date & time tracking

 Visit status (Planned / Completed / Cancelled)

 Customer feedback

 Follow-up reminder

 Auto update opportunity stage after visit

6️⃣ Follow-Ups & Tasks
6.1 Task Management

 Create follow-up tasks

 Call / Meeting / Site Visit / WhatsApp

 Due date & time

 Priority

 Assigned user

 Task status

6.2 Automation

 Auto task creation on stage change

 Overdue follow-up alerts

 Daily task dashboard for agents

7️⃣ Negotiation & Deal Tracking

 Offer price tracking

 Counter offer history

 Final agreed price

 Discount approvals (Manager/Admin)

 Booking amount tracking

 Agreement value

 Payment milestones

8️⃣ Closure & Post-Sales
8.1 Deal Closure

 Closed Won / Lost

 Lost reason (Price, Location, Builder, Delay, Loan issue)

 Closure date

 Final deal value

8.2 Post-Sales Tracking

 Agreement execution date

 Registration date

 Loan status

 Handover date

 Customer satisfaction notes

9️⃣ Mortgage & Loan Tracking (Optional Module)

 Loan required flag

 Bank name

 Loan amount

 Application date

 Approval status

 Disbursement tracking

 Loan commission tracking

🔟 Commission & Revenue Management

 Commission percentage per property

 Agent commission split

 Gross commission

 Net payout

 Commission status (Pending / Paid)

 Invoice generation

 Commission reports

1️⃣1️⃣ Communication & Notifications

 WhatsApp integration (future)

 Email notifications

 SMS notifications

 Internal activity log

 Lead activity timeline

1️⃣2️⃣ Analytics & Dashboards (Filament Widgets)
12.1 Sales Dashboard

 Leads by source

 Conversion funnel

 Opportunities by stage

 Closures (Monthly / Agent-wise)

 Revenue trends

 Site visit to closure ratio

12.2 Agent Performance

 Leads assigned vs closed

 Average closure time

 Follow-up compliance

 Commission earned

1️⃣3️⃣ Admin & System Controls

 Dynamic lead stages

 Dynamic opportunity stages

 Property categories management

 Source management

 Reason master (Lost / Delay)

 Audit logs

 Soft deletes

1️⃣4️⃣ Data & Integrations

 CSV import/export

 API ready structure

 Webhook support (Facebook / Google)

 Backup & restore

 GDPR / Data privacy compliance

1️⃣5️⃣ Technical Requirements (Copilot Friendly)

 RESTful service architecture

 Eloquent relationships clearly defined

 Filament Resources for all entities

 Policies for access control

 Events & listeners for stage changes

 Queue support for notifications

 Activity logging (Spatie)