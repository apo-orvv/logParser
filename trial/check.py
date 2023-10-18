import re
import pandas as pd
from datetime import datetime

def extract_info_from_log(log_content):
    entry_pattern = re.compile(r'(\d{2}:\d{2}:\d{2}) \((.*?)\) (.*?)\: "(.*?)" (.*?) (\(\d+ licenses\))')
    entries = re.findall(entry_pattern, log_content)
    data = []

    lines = log_content.strip().split('\n')
    
    current_date = None

    for line in lines:
        if re.match(r'(\d{2}:\d{2}:\d{2})', line):
            if "TIMESTAMP" in line:
                current_date = datetime.strptime(line.split()[-1], '%m/%d/%Y').strftime('%Y-%m-%d')
                continue

            timestamp_str, software, status, feature, user_machine, licenses = line
            timestamp = datetime.strptime(timestamp_str, '%H:%M:%S')
            licenses_count = int(re.search(r'\((\d+) licenses\)', licenses).group(1))
            
            data.append({
                "Date": current_date,
                "Time": timestamp.strftime('%H:%M:%S'),
                "Software": software,
                "Status": status,
                "Feature": feature,
                "User Machine": user_machine,
                "Licenses": licenses_count
            })

    return pd.DataFrame(data)

def main():
    with open("license.log", 'r') as log_file:
        log_content = log_file.read()

    df = extract_info_from_log(log_content)
    
    output_filename = "info.csv"
    df.to_csv(output_filename, index=False)
    
    print(f"Extracted information saved to {output_filename}")

if __name__ == "__main__":
    main()
