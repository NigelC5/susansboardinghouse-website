import tkinter as tk
from tkinter import messagebox
import qrcode
from PIL import Image, ImageTk

# Function to generate QR code for GCash payment
def generate_qr_code(payment_info):
    qr = qrcode.QRCode(
        version=1,
        error_correction=qrcode.constants.ERROR_CORRECT_L,
        box_size=10,
        border=4,
    )
    qr.add_data(payment_info)
    qr.make(fit=True)
    
    img = qr.make_image(fill="black", back_color="white")
    return img

# Payment portal function that takes tenant_id and amount as parameters
def open_payment_portal(tenant_id, amount_due, gcash_account):
    window = tk.Tk()
    window.title("Payment Portal")
    window.geometry("400x500")
    
    label_tenant = tk.Label(window, text=f"Tenant ID: {tenant_id}", font=('Arial', 12))
    label_tenant.pack(pady=10)
    
    label_amount = tk.Label(window, text=f"Amount Due: PHP {amount_due}", font=('Arial', 12))
    label_amount.pack(pady=10)
    
    label_qr = tk.Label(window, text="Scan this QR code to pay via GCash:")
    label_qr.pack(pady=5)

    qr_image = generate_qr_code(gcash_account)
    qr_image.save("gcash_qr_code.png")
    
    img = Image.open("gcash_qr_code.png")
    img = img.resize((200, 200))
    img_tk = ImageTk.PhotoImage(img)
    
    qr_label = tk.Label(window, image=img_tk)
    qr_label.image = img_tk
    qr_label.pack(pady=10)

    label_reference = tk.Label(window, text="Reference Number:")
    label_reference.pack(pady=5)
    
    entry_reference = tk.Entry(window)
    entry_reference.pack(pady=5)

    label_receipt = tk.Label(window, text="Upload Receipt (File Path):")
    label_receipt.pack(pady=5)
    
    entry_receipt = tk.Entry(window)
    entry_receipt.pack(pady=5)

    def process_payment():
        reference_number = entry_reference.get()
        receipt = entry_receipt.get()
        
        if reference_number and receipt:
            messagebox.showinfo("Payment", f"Payment of PHP {amount_due} received with reference {reference_number}.")
            window.destroy()
        else:
            messagebox.showwarning("Missing Info", "Please enter a reference number and upload the receipt.")

    btn_pay_now = tk.Button(window, text="Submit Payment", command=process_payment)
    btn_pay_now.pack(pady=20)
    
    window.mainloop()

# To test or manually open the portal, call open_payment_portal with values
if __name__ == "__main__":
    tenant_id = 123  # Replace with real tenant ID
    amount_due = 5000  # Replace with real amount due
    gcash_account = "GCash Account: 09123456789"
    
    open_payment_portal(tenant_id, amount_due, gcash_account)
