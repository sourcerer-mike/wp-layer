#!/usr/bin/env bash

baseDir=$(git rev-parse --show-toplevel)

wpContent=${wpContent-"wp-content"}

for D in $(ls -1 $wpContent/themes); do
	TARGET=$wpContent/themes/$D

	if [[ ! -d $TARGET ]]; then
		continue;
	fi

	if [[ -d $D/.git ]]; then
	    # Skip directories that are under version control.
		continue;
	fi

	git add --all $TARGET > /dev/null;
	git commit -m "Theme aktualisiert - $D" $TARGET;
done
