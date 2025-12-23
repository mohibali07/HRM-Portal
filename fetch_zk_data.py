import sys
import json
from zk import ZK
from datetime import datetime

def fetch_data(ip, start_date, end_date):
    zk = ZK(ip, port=4370, timeout=20)
    try:
        conn = zk.connect()
        
        # 1. Fetch Users
        users = conn.get_users()
        user_map = {u.user_id: u.name for u in users}
        
        # 2. Fetch Attendance
        attendance = conn.get_attendance()
        
        data = []
        try:
            start = datetime.strptime(start_date, '%Y-%m-%d').date()
            end = datetime.strptime(end_date, '%Y-%m-%d').date()
        except ValueError:
             print(json.dumps({'status': 'error', 'msg': 'Invalid date format'}))
             return

        for punch in attendance:
            # punch.timestamp is a datetime object
            punch_date = punch.timestamp.date()
            
            if start <= punch_date <= end:
                data.append({
                    'uid': punch.user_id,
                    'name': user_map.get(punch.user_id, 'Unknown'),
                    'timestamp': punch.timestamp.strftime('%Y-%m-%d %H:%M:%S'),
                    'state': punch.status
                })
        
        print(json.dumps({'status': 'success', 'data': data, 'user_map': user_map}))
        
    except Exception as e:
        print(json.dumps({'status': 'error', 'msg': str(e)}))
    finally:
        if 'conn' in locals():
            conn.disconnect()

if __name__ == "__main__":
    if len(sys.argv) < 4:
        print(json.dumps({'status': 'error', 'msg': 'Missing arguments: ip start_date end_date'}))
    else:
        fetch_data(sys.argv[1], sys.argv[2], sys.argv[3])
