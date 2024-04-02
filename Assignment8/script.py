import re
from datetime import datetime
from collections import defaultdict
import matplotlib.pyplot as plt
import matplotlib.dates

def parse_apache_access_logs(log_file_path):
    log_pattern = re.compile(r'(?P<ip>[\d\.]+) - - \[.*?\] "(?P<request>.*?)" \d+ \d+ "(?P<user_agent>.*?)"')

    page_stats = defaultdict(lambda: {'count': 0, 'ip_addresses': set(), 'user_agents': set(), 'browsers': set(), 'timestamps': []})
    
    with open(log_file_path, 'r') as log_file:
        for line in log_file:
            match = log_pattern.match(line)
            if match:
                ip = match.group('ip')
                request = match.group('request')
                user_agent = match.group('user_agent')

                page_match = re.match(r'GET /([^?\s]+)', request)
                if page_match:
                    page = page_match.group(1)

                    # Extract browser information from user agent
                    browser_match = re.search(r'\((.*?)\)', user_agent)
                    browser = browser_match.group(1) if browser_match else 'Unknown'

                    # Update page access statistics
                    page_stats[page]['count'] += 1
                    page_stats[page]['ip_addresses'].add(ip)
                    page_stats[page]['user_agents'].add(user_agent)
                    page_stats[page]['browsers'].add(browser)
                    timestamp_str = line.split('[')[1].split(']')[0]
                    timestamp = datetime.strptime(timestamp_str, '%d/%b/%Y:%H:%M:%S %z')
                    page_stats[page]['timestamps'].append(timestamp)

    return page_stats

def parse_apache_error_logs(log_file_path):
    error_pattern = re.compile(r'\[.*?\] \[.*?\] \[.*?\] \[client (?P<ip>[\d\.]+)\] (?P<error_message>.*?)$')

    error_stats = defaultdict(lambda: {'count': 0, 'ip_addresses': set(), 'error_messages': set(), 'timestamps': []})

    with open(log_file_path, 'r') as log_file:
        for line in log_file:
            match = error_pattern.match(line)
            if match:
                ip = match.group('ip')
                error_message = match.group('error_message')

                # Update error statistics
                error_stats[ip]['count'] += 1
                error_stats[ip]['ip_addresses'].add(ip)
                error_stats[ip]['error_messages'].add(error_message)
                timestamp_str = line.split('[')[1].split(']')[0]
                timestamp = datetime.strptime(timestamp_str, '%a %b %d %H:%M:%S %Y')
                error_stats[ip]['timestamps'].append(timestamp)

    return error_stats

def plot_timeline(timestamps, title):
    sorted_timestamps = sorted(timestamps)  # Sort timestamps
    date_numbers = matplotlib.dates.date2num(sorted_timestamps)  # Convert timestamps to a format matplotlib can handle

    plt.plot_date(date_numbers, range(len(sorted_timestamps)), 'o-', label=title, linestyle='-')
    plt.gcf().autofmt_xdate()
    plt.title(title)
    plt.xlabel('Timestamp')
    plt.ylabel('Count')
    plt.legend()
    plt.show()

if __name__ == "__main__":
    apache_access_log_path = "/var/log/apache2/access_log"  # Replace with the actual path to your Apache access log file
    apache_error_log_path = "/var/log/apache2/error_log"  # Replace with the actual path to your Apache error log file

    # Parse Apache access logs for page access statistics
    page_statistics = parse_apache_access_logs(apache_access_log_path)

    # Print and plot page access statistics
    for page, stats in page_statistics.items():
        print(f"Page: {page}")
        print(f"Access Count: {stats['count']}")
        print(f"Unique IP Addresses: {len(stats['ip_addresses'])}")
        print(f"Unique User Agents: {len(stats['user_agents'])}")
        print(f"Unique Browsers: {', '.join(stats['browsers'])}")  # New line to print browsers
        print(f"First Access Timestamp: {min(stats['timestamps'])}")
        print(f"Last Access Timestamp: {max(stats['timestamps'])}")
        print(f"IP Addresses: {', '.join(stats['ip_addresses'])}")  # New line to print IP addresses
        print("\n")

    # Plot timeline for page access
    for page, stats in page_statistics.items():
        plot_timeline(stats['timestamps'], f"Page Access Timeline - {page}")

    # Parse Apache error logs for error statistics
    error_statistics = parse_apache_error_logs(apache_error_log_path)

    # Print and plot error statistics
    print("Error Statistics:")
    for ip, stats in error_statistics.items():
        print(f"IP Address: {ip}")
        print(f"Total Errors: {stats['count']}")
        print(f"Unique Error Messages: {len(stats['error_messages'])}")
        print(f"First Error Timestamp: {min(stats['timestamps'])}")
        print(f"Last Error Timestamp: {max(stats['timestamps'])}")
        print(f"Error Messages: {', '.join(stats['error_messages'])}")  # New line to print error messages
        print(f"IP Addresses: {', '.join(stats['ip_addresses'])}")  # New line to print IP addresses
        print("\n")

        # Plot timeline for errors
        plot_timeline(stats['timestamps'], f"Error Timeline - {ip}")
