import sys
from datetime import datetime, timedelta

def calculate_dates(date_in, rent_payment, due_day):
    date_in = datetime.strptime(date_in, '%Y-%m-%d')
    start_date = date_in.replace(day=due_day)
    
    if rent_payment <= 1200:
        end_date = start_date
    else:
        remaining_payment = rent_payment - 1200
        months_paid = remaining_payment // 1200
        additional_days = remaining_payment % 1200
        end_date = start_date + timedelta(days=additional_days)
        end_date = end_date.replace(month=end_date.month + months_paid)
        
    period_covered = f"{start_date.strftime('%B')} {due_day}, {start_date.year} - {end_date.strftime('%B')} {due_day}, {end_date.year}"
    
    return start_date.strftime('%Y-%m-%d'), end_date.strftime('%Y-%m-%d'), period_covered

# Get arguments from command line
date_in = sys.argv[1]
rent_payment = int(sys.argv[2])
due_day = int(sys.argv[3])

# Calculate dates
start_date, end_date, period_covered = calculate_dates(date_in, rent_payment, due_day)
print(start_date, end_date, period_covered)
