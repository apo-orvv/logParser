import re
import pandas as pd
from datetime import datetime

def extract_info_from_log(log_content):
    lines = log_content.strip().split('\n')
    
    data = []
    current_date = None
    
    for line in lines:
        timestamp_match = re.match(r'(\d{2}:\d{2}:\d{2})', line)
        if timestamp_match:
            timestamp_str = timestamp_match.group(1)
            timestamp = datetime.strptime(timestamp_str, '%H:%M:%S')
            
            if "TIMESTAMP" in line:
                current_date = datetime.strptime(line.split()[-1], '%m/%d/%Y').strftime('%Y-%m-%d')
                continue
            
            if current_date is not None:
                parts = line.split('"')
                if len(parts) >= 2:
                    software = parts[1]
                    rest = parts[2]
                    parts = rest.split()
                    user_machine = parts[-2] if parts[-1] == "(8" or parts[-1] == "(4" else "Unknown"
                    licenses = parts[-1].replace("(", "").replace(")", "")
                    
                    data.append({
                        "Date": current_date,
                        "Time": timestamp.strftime('%H:%M:%S'),
                        "Software": software,
                        "Feature": parts[0],
                        "User Machine": user_machine,
                        "Licenses": licenses
                    })
    
    return pd.DataFrame(data)

def main():
    with open("license.log", 'r') as log_file:
        log_content = log_file.read()

    df = extract_info_from_log(log_content)
    
    output_filename = "license_info2.csv"
    df.to_csv(output_filename, index=False)
    
    print(f"Extracted information saved to {output_filename}")

if __name__ == "__main__":
    main()
