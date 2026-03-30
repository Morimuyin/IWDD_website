#!/bin/bash

# query the NCBI protein database for protein family and taxonomy group
# return max=20 results
# code reference: the parser from fasta to JSON is generated with the help of ChatGPT

FAMILY=$1
TAXONOMY=$2

QUERY="$FAMILY AND $TAXONOMY"
# echo $QUERY

esearch -db protein -query "$QUERY" \
| efetch -format fasta \
| awk '
BEGIN {
    print "["
    first=1
    count=0
}

# header line starts a new record
/^>/ {
    # if we already have a record, print it
    if (seq != "") {

        if (!first) print ","
        first=0

        printf "{\"id\":\"%s\",\"name\":\"%s\",\"sequence\":\"%s\",\"length\":%d}",
            id, name, seq, length(seq)

        count++

        # STOP AFTER 20 RESULTS
        if (count >= 20) {
            print "\n]"
            exit
        }
    }

    seq=""

    id=$1
    sub(/^>/, "", id)

    name=$0
    sub(/^>[^ ]+ /, "", name)

    next
}

# sequence lines
{
    seq = seq $0
}

END {
    if (count < 20 && seq != "") {
        if (!first) print ","
        printf "{\"id\":\"%s\",\"name\":\"%s\",\"sequence\":\"%s\",\"length\":%d}",
            id, name, seq, length(seq)
    }
    print "\n]"
}'
