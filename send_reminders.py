import smtplib
from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText
import mysql.connector
from datetime import datetime, timedelta

def send_email_reminder(recipient_email, tenant_name, payment_due_date, tenant_id):
    try:
        sender_email = "susansboardinghouse@gmail.com"
        sender_password = "imgexqaaxmlwcslt"
        smtp_server = "smtp.gmail.com"
        smtp_port = 587

        msg = MIMEMultipart()
        msg['From'] = sender_email
        msg['To'] = recipient_email
        msg['Subject'] = 'Reminder: Upcoming Due Date'

        body = f"""
        Dear {tenant_name},

        This is a reminder that your upcoming due date is on {payment_due_date}. 
        Please make sure to settle your dues on time. 

        To pay, please follow this link to the payment portal:
        http://yourdomain.com/payment?tenant_id={tenant_id}

        Regards,
        Your Landlord
        """
        msg.attach(MIMEText(body, 'plain'))

        server = smtplib.SMTP(smtp_server, smtp_port)
        server.starttls()
        server.login(sender_email, sender_password)

        server.sendmail(sender_email, recipient_email, msg.as_string())
        server.quit()

        print(f"Email reminder sent successfully to {recipient_email}")
    except Exception as e:
        print(f"Error sending email reminder to {recipient_email}: {str(e)}")

# Database connection and selecting tenants whose payment is due
conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="boarding_house"
)

# Get today's date
today = datetime.now().date()
print(f"Today's date: {today}")

cursor = conn.cursor(dictionary=True)
cursor.execute("""
    SELECT id, lastname, firstname, middlename, email, date_in, due_day 
    FROM tenants 
    WHERE email_sent != 'Sent'
""")
tenants = cursor.fetchall()

print(f"Tenants fetched: {len(tenants)}")

for tenant in tenants:
    tenant_name = f"{tenant['lastname']}, {tenant['firstname']} {tenant['middlename']}"
    recipient_email = tenant['email']
    
    # Fetching tenant data
    date_in = tenant['date_in']
    
    print(f"Tenant ID: {tenant['id']}, Date In: {date_in}")

    # Calculate the due date as one month after date_in
    if date_in:
        # Assume due_day represents the day of the month the payment is due
        due_day = tenant['due_day']
        due_date = date_in.replace(day=due_day) + timedelta(days=30)  # Add 30 days to get to the same day next month
        if due_date.day != due_day:  # If next month doesn't have the same day, set it to the last day of that month
            due_date = due_date.replace(day=1) + timedelta(days=32)
            due_date = due_date.replace(day=1) - timedelta(days=1)

    else:
        due_date = None
    
    print(f"Processing tenant: {tenant_name}, Calculated Due date: {due_date}")

    # Check if today is 7 days before the due date
    if due_date and (due_date - today).days == 7:  
        tenant_id = tenant['id']
        print(f"Sending reminder to {recipient_email} for due date {due_date}")
        send_email_reminder(recipient_email, tenant_name, due_date.strftime('%Y-%m-%d'), tenant_id)

        # Mark email as sent in the database
        cursor.execute("UPDATE tenants SET email_sent = 'Sent' WHERE id = %s", (tenant_id,))
        conn.commit()
    else:
        print(f"No reminder sent to {tenant_name}. Due date is not in 7 days.")

conn.close()
