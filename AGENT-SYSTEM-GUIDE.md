# 🎯 ANS Realty - Complete Agent Management System

## 🚀 Quick Start

### Run This Command:
```bash
SETUP-AGENT-SYSTEM.bat
```

This will:
1. Fresh migrate all tables
2. Seed master data (sources, statuses, stages)
3. Create comprehensive sample data
4. Clear all caches

---

## 📊 What Gets Created

### 1. **Users (Internal Team)**
- **Admin User** (admin@ansrealty.com)
  - Full system access
  - Employee Code: EMP-00001

- **Rajesh Kumar** (rajesh@ansrealty.com)
  - Manager role
  - Oversees all sales team
  - Employee Code: EMP-00002
  - Target: ₹50L/month

- **Sales Team (3 Executives)**
  - **Priya Sharma** (priya@ansrealty.com) - Target: ₹20L/month
  - **Amit Patel** (amit@ansrealty.com) - Target: ₹18L/month
  - **Sneha Reddy** (sneha@ansrealty.com) - Target: ₹22L/month

- **Vikram Singh** (vikram@ansrealty.com)
  - Telecaller role
  - Handles initial lead contact

**All Passwords:** `password`

---

### 2. **External Agents (5 Agents)**
Each agent is assigned to an employee who manages them:

| Agent Code | Name | Company | Commission | Assigned To |
|------------|------|---------|------------|-------------|
| AGT-00001 | Suresh Properties | Suresh Real Estate | 2.5% | Priya |
| AGT-00002 | Meera Builders | Meera Realty Group | 2.0% | Priya |
| AGT-00003 | Ramesh Kumar | Independent | 1.5% | Amit |
| AGT-00004 | Kavita Homes | Kavita Property Solutions | 2.0% | Amit |
| AGT-00005 | Anil Consultants | Anil & Associates | 1.8% | Sneha |

**Key Features:**
- Each agent has bank details for commission payments
- PAN numbers for GST/TDS compliance
- Mobile & email for communication
- Joining dates tracked
- Status tracking (Active/Inactive/Suspended)

---

### 3. **Builders (5 Top Builders)**
- Lodha Group
- Godrej Properties
- Hiranandani Group
- Oberoi Realty
- Kalpataru Group

Each with RERA numbers and contact details

---

### 4. **Properties (20 Properties)**
Variety across:
- **Types:** Flats, Villas, Penthouses, Commercial
- **Locations:** Bandra West, Andheri East, Powai, Malad West, Thane West, Navi Mumbai
- **Price Range:** ₹90L - ₹6Cr
- **Configurations:** 2BHK to 5BHK
- **Status:** Ready to Move & Under Construction

**Features:**
- Featured & Hot property flags
- Amenities list
- Floor plans ready
- View count tracking
- SEO-friendly descriptions

---

### 5. **Leads (10 Leads)**
Realistic customer data:
- Mix of Website & Referral sources
- Assigned to different employees
- Some have agents attached (referral commission)
- Various priorities (Hot/Warm/Cold)
- Budget ranges from ₹30L to ₹1Cr
- Contact history tracked

---

### 6. **Opportunities (8 Opportunities)**
Active sales pipeline:
- Linked to leads and properties
- Different stages (Requirement, Shortlisted, Negotiation, etc.)
- Expected values and close dates
- Probability percentages
- Agent tracking for commission

---

### 7. **Bookings (3 Sample Bookings)**

#### Booking 1: Token Stage
- Customer: Rahul Verma
- Property Value: ₹85L
- Token: ₹1L received
- Status: Token Confirmed
- Commission: Pending calculation

#### Booking 2: Agreement Stage
- Customer: Anjali Mehta
- Property Value: ₹1.2Cr
- Token: ₹2L
- Booking Amount: ₹12L
- Agreement Signed: AGR-2026-001
- Commission: Pending approval

#### Booking 3: Registration Complete
- Customer: Sanjay Gupta
- Property Value: ₹95L
- Full payment milestones completed
- Registration: REG-2026-001
- Commission: Approved (ready for payment)

---

## 🎯 Agent Management Workflow

### For External Agents:

#### **Step 1: Agent Onboarding**
```
Admin/Manager → Agents Resource → Create New Agent
```
- Enter agent details
- Upload documents (PAN, Aadhar, Bank)
- Set commission structure (% or Fixed)
- Assign to an employee (relationship manager)
- Set status to Active

#### **Step 2: Lead Assignment**
```
Employee → Receives lead from agent
Employee → Creates lead in system
Employee → Links agent to the lead
```
- Lead automatically tagged with agent
- Commission eligibility established

#### **Step 3: Opportunity Creation**
```
Employee → Converts lead to opportunity
System → Auto-copies agent relationship
```
- Agent commission % locked in
- Tracking begins

#### **Step 4: Site Visit**
```
Employee → Schedules site visit
Agent → Can accompany (optional)
Employee → Marks visit status
```
- Visit scheduled, done, cancelled tracking
- Feedback captured

#### **Step 5: Booking Creation**
```
Employee → Creates booking from opportunity
System → Auto-calculates agent commission
```
- Token amount tracked
- Commission calculated based on property value
- Commission status: Pending

#### **Step 6: Booking Progress**
```
Employee → Updates booking stage:
- Token Confirmed
- Agreement Signed
- Registration Done
- Possession Done
```
- Each stage tracked with dates & documents
- Commission status updates

#### **Step 7: Commission Approval**
```
Manager → Reviews bookings
Manager → Approves commission
```
- Commission moves to "Approved" status
- Ready for payment processing

#### **Step 8: Payment & Invoice**
```
Manager/Admin → Marks commission as "Paid"
System → Generates invoice
```
- Payment date & reference recorded
- Invoice number auto-generated
- Agent receives payment confirmation

---

## 📱 Employee-Agent Relationship

### Why Each Agent Needs an Assigned Employee?

1. **Relationship Management**
   - Single point of contact for agent
   - Builds trust and accountability
   - Better communication

2. **Lead Quality Control**
   - Employee verifies agent's leads
   - Ensures proper qualification
   - Maintains data quality

3. **Commission Tracking**
   - Employee tracks agent's deals
   - Monitors performance
   - Facilitates timely payments

4. **Performance Monitoring**
   - Each employee knows their agents' performance
   - Can provide support where needed
   - Identify top-performing agents

5. **Workload Distribution**
   - Agents distributed among team members
   - No single point of failure
   - Balanced responsibilities

---

## 💰 Commission Calculation

### Automatic Calculation:
```
When booking is created:
1. System checks if agent is linked
2. Reads agent's commission structure:
   - If Percentage: (Property Value × Commission %) = Commission Amount
   - If Fixed: Fixed Amount regardless of property value
3. Stores calculated commission
4. Sets status to "Pending"
```

### Example:
```
Property Value: ₹1,00,00,000 (₹1 Crore)
Agent Commission: 2%
Calculated: ₹2,00,000

Less: TDS (if applicable)
Net Payable: Calculated amount
```

---

## 📊 Reports Available

### 1. Agent Performance Report
- Total deals brought
- Total value generated
- Commission earned
- Pending commissions
- Active vs closed deals

### 2. Employee-wise Agent Report
- Agents assigned to each employee
- Performance of each agent under them
- Commission processed
- Pending approvals

### 3. Booking Pipeline Report
- All bookings by stage
- Expected closures
- Stuck deals
- Commission liability

### 4. Commission Report
- Pending approvals
- Approved but unpaid
- Paid commissions
- Agent-wise breakdown
- Month-wise summary

---

## 🔐 Access Control

### Admin
- Full access to all modules
- Approve commissions
- Manage all agents
- View all reports

### Manager
- Manage team's agents
- Approve commissions for team
- View team reports
- Assign leads to team

### Employee
- Manage assigned agents only
- Create bookings
- Update booking status
- Request commission approval

### Telecaller
- View leads
- Make calls
- Update lead status
- No agent/booking access

---

## 📋 Next Steps (After Setup)

1. **Login to Admin Panel**
   ```
   URL: http://localhost:8000/admin
   Email: admin@ansrealty.com
   Password: password
   ```

2. **Explore the Data**
   - Check Leads → See sample customers
   - Check Properties → Browse listings
   - Check Opportunities → View sales pipeline
   - Check Bookings → See transactions
   - Check Agents → Manage external agents

3. **Test the Workflow**
   - Create a new lead
   - Assign to an agent
   - Convert to opportunity
   - Create booking
   - Track commission

4. **Customize**
   - Add your actual agents
   - Import your properties
   - Configure commission structures
   - Set up email notifications

---

## 🎓 Best Practices

### For Agent Management:
1. Always assign an employee to new agents
2. Verify bank details before first payment
3. Collect all documents (PAN, Aadhar, Bank proof)
4. Review agent performance monthly
5. Set clear commission structures upfront

### For Booking Management:
1. Update booking stage promptly
2. Upload agreement & registration documents
3. Track all payment milestones
4. Approve commissions within 7 days of registration
5. Generate invoices immediately after payment

### For Commission Processing:
1. Verify booking is fully closed
2. Check for any disputes
3. Confirm TDS deduction if applicable
4. Process payment within agreed timeline
5. Send payment confirmation to agent

---

## 🆘 Troubleshooting

### Issue: Agent not showing in dropdown
**Solution:** Check agent status is "Active"

### Issue: Commission not auto-calculating
**Solution:** Ensure agent is linked to lead before creating opportunity

### Issue: Cannot approve commission
**Solution:** Check booking stage is at least "Agreement Signed"

### Issue: Agent can't see their data
**Solution:** Agents don't have portal access. Employee manages on their behalf.

---

## 📞 Support

For issues or questions:
- Check this documentation first
- Review sample data for examples
- Test with sample bookings provided

---

**System Ready! Your CRM is now fully functional with complete agent management.**

All passwords: `password`
