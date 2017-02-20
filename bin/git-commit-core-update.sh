#!/usr/bin/env bash

# List of WordPress core files
TARGETS=(
	index.php
	wp-activate.php
	wp-admin/
	wp-blog-header.php
	wp-comments-post.php
	wp-config-sample.php
	wp-content/index.php
	wp-content/languages
	wp-content/plugins/index.php
	wp-content/themes/index.php
	wp-content/themes/twentyeleven
	wp-content/themes/twentytwelve
	wp-content/themes/twentythirteen
	wp-content/themes/twentyfourteen
	wp-content/themes/twentyfifteen
	wp-content/themes/twentysixteen
	wp-cron.php
	wp-includes/
	wp-links-opml.php
	wp-load.php
	wp-login.php
	wp-mail.php
	wp-register.php
	wp-settings.php
	wp-signup.php
	wp-trackback.php
	xmlrpc.php
);

removeFiles=(
	license.txt
	liesmich.html
	README
	readme.html
	wp-config-sample.php
);

# Clean-up staged files
git reset HEAD -- . > /dev/null

status=$?

if [ ${status} -ne 0 ]; then
	echo "ERROR: Could not reset the branch to HEAD."
fi

# Add if exists
for T in "${TARGETS[@]}"; do
	if [[ ! -d ${T} && ! -f ${T} ]]; then
		continue;
	fi
	
	# echo Adding $T
	git add --all $T
done

for singleFile in "${removeFiles[@]}"; do
	if [[ ! -f $singleFile ]]; then
		continue;
	fi

	git rm -rf $singleFile &> /dev/null
done

previousVersion=$(git show HEAD:wp-includes/version.php | grep "wp_version =" | egrep -o "[0-9\.]+")
currentVersion=$(cat wp-includes/version.php | grep "wp_version =" | egrep -o "[0-9\.]+")

echo Nice!
echo ""
echo "To see what is not yet in the commit type:"
echo "	git diff --name-only"
echo ""
echo "Once you are done do the magic trick:"
echo "	git commit -m 'WordPress Core Update ${previousVersion} to ${currentVersion}'"
echo ""
