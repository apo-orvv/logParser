import re
from datetime import datetime

log_content = """
18:48:22 (ABAQUSLM) DENIED: "abaqus" askmanson@node353.igcar.gov.in  (25 licenses) (Users are queued for this feature. (-24,332))
18:48:22 (ABAQUSLM) DENIED: "explicit" askmanson@node353.igcar.gov.in  (25 licenses) (Users are queued for this feature. (-24,332))
18:49:27 (lmgrd) TIMESTAMP 4/28/2023
18:54:29 (ABAQUSLM) TIMESTAMP 4/28/2023
19:03:52 (ABAQUSLM) DENIED: "abaqus" askmanson@node353.igcar.gov.in  (25 licenses) (Users are queued for this feature. (-24,332))
19:03:52 (ABAQUSLM) DENIED: "explicit" askmanson@node353.igcar.gov.in  (25 licenses) (Users are queued for this feature. (-24,332))
19:19:22 (ABAQUSLM) DENIED: "abaqus" askmanson@node353.igcar.gov.in  (25 licenses) (Users are queued for this feature. (-24,332))
19:19:22 (ABAQUSLM) DENIED: "explicit" askmanson@node353.igcar.gov.in  (25 licenses) (Users are queued for this feature. (-24,332))
19:23:41 (ABAQUSLM) 1682693477/1/6.17-1/30/sta_par/9179/kulbir/unknown/node350.igcar.gov.in
"""

# Define regular expressions to extract relevant information
entry_pattern = re.compile(r'(\d{2}:\d{2}:\d{2}) \((.*?)\) (.*?)\: "(.*?)" (.*?) (\(\d+ licenses\))')

entries = re.findall(entry_pattern, log_content)

# Process and print the extracted information
for entry in entries:
    timestamp_str, software, status, feature, user_machine, licenses = entry
    timestamp = datetime.strptime(timestamp_str, '%H:%M:%S')
    licenses_count = re.search(r'\((\d+) licenses\)', licenses).group(1)
    
    print("Date:", timestamp.strftime('%Y-%m-%d'))
    print("Time:", timestamp.strftime('%H:%M:%S'))
    print("Software:", software)
    print("Status:", status)
    print("Feature:", feature)
    print("User Machine:", user_machine)
    print("Licenses:", licenses_count)
    print("-" * 40)
