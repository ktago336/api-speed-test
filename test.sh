#!/bin/bash

# Check if jq is installed
if ! command -v jq &> /dev/null
then
    echo "jq is not installed. Please install jq and try again."
    exit 1
fi

# Check if the correct number of arguments are provided
if [ "$#" -lt 2 ] || [ "$#" -gt 3 ]; then
    echo "Usage: $0 <url> <number_of_requests> [input_file]"
    exit 1
fi

# Assign command-line arguments to variables
url=$1
number_of_requests=$2
input_file=${3:-input.json} # Default to input.json if not provided

# Initialize variables
total_time=0
response_times=()

# Send requests and measure response times
for ((i=1; i<=number_of_requests; i++))
do
    start_time=$(date +%s%N)
    curl -s -o /dev/null -X POST -H "Content-Type: application/json" -d @"$input_file" $url
    end_time=$(date +%s%N)

    response_time=$(( (end_time - start_time) / 1000000 )) # Convert to milliseconds
    response_times+=($response_time)
    total_time=$((total_time + response_time))
done

# Calculate average response time
average_time=$((total_time / number_of_requests))

# Write to result.json
result_file="result.json"
jq -n --arg avg "$average_time" --argjson times "$(printf '%s\n' "${response_times[@]}" | jq -s '.')" \
    '{average: $avg, response_times: $times}' > $result_file

echo "Results written to $result_file"
