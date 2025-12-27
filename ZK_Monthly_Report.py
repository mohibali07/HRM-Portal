import tkinter as tk
from tkinter import ttk, messagebox
from zk import ZK
import pandas as pd
from datetime import datetime
import calendar

class ZKApp:
    def __init__(self, root):
        self.root = root
        self.root.title("ZK Attendance Manager (Full Logs)")
        self.root.geometry("520x500")
        
        self.user_map = {} 
        
        # --- STYLE ---
        title_font = ("Arial", 11, "bold")
        
        # --- 1. CONNECTION ---
        tk.Label(root, text="Step 1: Connect & Load Users", font=title_font, fg="#333").pack(pady=(10, 5))
        
        frame_conn = tk.Frame(root)
        frame_conn.pack(pady=5)
        
        tk.Label(frame_conn, text="IP:").pack(side=tk.LEFT)
        self.ip_entry = tk.Entry(frame_conn, width=15)
        self.ip_entry.insert(0, "192.168.0.194")
        self.ip_entry.pack(side=tk.LEFT, padx=5)

        self.btn_load = tk.Button(frame_conn, text="Load Employee List", bg="#FF9800", fg="white",
                                  command=self.load_users_from_device)
        self.btn_load.pack(side=tk.LEFT, padx=5)

        # --- 2. FILTERS ---
        tk.Label(root, text="Step 2: Select Filters", font=title_font, fg="#333").pack(pady=(20, 5))
        
        tk.Label(root, text="Target Employee:").pack()
        self.employee_cb = ttk.Combobox(root, width=35, state="readonly")
        self.employee_cb.set("Load Employees First...") 
        self.employee_cb.pack(pady=5)

        frame_date = tk.Frame(root)
        frame_date.pack(pady=10)

        tk.Label(frame_date, text="Year:").pack(side=tk.LEFT)
        current_year = datetime.now().year
        self.year_cb = ttk.Combobox(frame_date, values=[current_year, current_year-1], width=6)
        self.year_cb.current(0)
        self.year_cb.pack(side=tk.LEFT, padx=5)

        tk.Label(frame_date, text="Month:").pack(side=tk.LEFT)
        months = list(calendar.month_name)[1:]
        self.month_cb = ttk.Combobox(frame_date, values=months, width=10)
        self.month_cb.current(datetime.now().month - 1)
        self.month_cb.pack(side=tk.LEFT, padx=5)

        # --- 3. GENERATE ---
        self.status_label = tk.Label(root, text="Ready", fg="gray", font=("Arial", 9))
        self.status_label.pack(pady=15)

        self.btn_download = tk.Button(root, text="Generate Detailed Report", 
                                      bg="#4CAF50", fg="white", font=("Arial", 12, "bold"),
                                      height=2, width=30,
                                      command=self.generate_report)
        self.btn_download.pack(pady=10)

    def update_status(self, text, color):
        self.status_label.config(text=text, fg=color)
        self.root.update()

    def get_zk_connection(self):
        ip = self.ip_entry.get()
        return ZK(ip, port=4370, timeout=10)

    def load_users_from_device(self):
        self.update_status("Connecting...", "blue")
        zk = self.get_zk_connection()
        try:
            conn = zk.connect()
            users = conn.get_users()
            self.user_map = {}
            dropdown_values = ["All Employees"]

            for user in users:
                self.user_map[user.user_id] = user.name
                dropdown_values.append(f"{user.user_id} - {user.name}")

            self.employee_cb['values'] = dropdown_values
            self.employee_cb.current(0)
            self.update_status(f"Loaded {len(users)} employees.", "green")
            messagebox.showinfo("Success", "Employee list loaded.")
        except Exception as e:
            self.update_status("Connection Failed", "red")
            messagebox.showerror("Error", str(e))
        finally:
            if 'conn' in locals(): conn.disconnect()

    def generate_report(self):
        selected_emp_string = self.employee_cb.get()
        if "Load Employees" in selected_emp_string:
            messagebox.showwarning("Stop", "Please click 'Load Employee List' first.")
            return

        ip = self.ip_entry.get()
        selected_year = int(self.year_cb.get())
        month_idx = list(calendar.month_name).index(self.month_cb.get())
        
        target_user_id = None
        if selected_emp_string != "All Employees":
            target_user_id = selected_emp_string.split(" - ")[0]

        self.update_status("Downloading data...", "blue")
        zk = self.get_zk_connection()

        try:
            conn = zk.connect()
            attendance = conn.get_attendance()
            
            if not self.user_map:
                users = conn.get_users()
                self.user_map = {u.user_id: u.name for u in users}

            self.update_status("Processing...", "blue")
            daily_data = {}
            count_records = 0

            for punch in attendance:
                if punch.timestamp.year == selected_year and punch.timestamp.month == month_idx:
                    if target_user_id and punch.user_id != target_user_id:
                        continue 

                    count_records += 1
                    uid = punch.user_id
                    date_obj = punch.timestamp
                    date_str = date_obj.strftime('%Y-%m-%d')
                    day_name = date_obj.strftime('%A')
                    key = f"{uid}_{date_str}"
                    
                    if key not in daily_data:
                        daily_data[key] = {
                            'ID': uid,
                            'Name': self.user_map.get(uid, "Unknown"),
                            'Date': date_str,
                            'Day': day_name,
                            'Punches': []
                        }
                    daily_data[key]['Punches'].append(punch.timestamp)

            if count_records == 0:
                self.update_status("No records found.", "orange")
                messagebox.showinfo("Result", "No records found.")
                return

            final_rows = []
            for key, data in daily_data.items():
                times = sorted(data['Punches'])
                
                # 1. Basic In/Out
                check_in = times[0].strftime('%H:%M:%S')
                check_out = times[-1].strftime('%H:%M:%S') if len(times) > 1 else ""
                
                # 2. Detailed History (New Feature)
                # Create a string like "09:00, 13:00, 14:00, 18:00"
                all_punches_str = ", ".join([t.strftime('%H:%M') for t in times])
                
                # 3. Status
                status = "Present"
                if len(times) == 1: status = "Missing Out-Punch"

                final_rows.append({
                    "User ID": data['ID'],
                    "Name": data['Name'],
                    "Date": data['Date'],
                    "Day": data['Day'],
                    "Check-In": check_in,
                    "Check-Out": check_out,
                    "All Punches": all_punches_str, # <--- Added Here
                    "Status": status
                })

            df = pd.DataFrame(final_rows)
            # Reorder columns
            df = df[["User ID", "Name", "Date", "Day", "Check-In", "Check-Out", "All Punches", "Status"]]
            df = df.sort_values(by=["Name", "Date"])
            
            fname_part = f"{self.user_map.get(target_user_id, 'OneUser')}" if target_user_id else "All_Employees"
            filename = f"Attendance_{self.month_cb.get()}_{fname_part}.xlsx"
            
            df.to_excel(filename, index=False)
            
            self.update_status(f"Saved: {filename}", "green")
            messagebox.showinfo("Success", f"Report Generated!\nFile: {filename}")

        except Exception as e:
            self.update_status("Error", "red")
            messagebox.showerror("Error", str(e))
        finally:
            if 'conn' in locals(): conn.disconnect()

if __name__ == "__main__":
    root = tk.Tk()
    app = ZKApp(root)
    root.mainloop()