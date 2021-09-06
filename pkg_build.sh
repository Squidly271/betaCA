#!/bin/bash
tmpdir=/tmp/tmp.$(( $RANDOM * 19318203981230 + 40 ))
archive="/mnt/disks/WINDOWS10_GitHub/betaCA/archive"

version=$(date +"%Y.%m.%d")$1

mkdir -p $tmpdir

cd /tmp/GitHub/betaCA/source/community.applications/
chmod 0755 -R .
cp --parents -f $(find . -type f ! \( -iname "pkg_build.sh" -o -iname "sftp-config.json"  \) ) $tmpdir/
cd $tmpdir
makepkg -l y -c y /tmp/GitHub/betaCA/archive/community.applications-${version}-x86_64-1.txz
#rm -rf $tmpdir
echo "MD5:"
md5sum /tmp/GitHub/betaCA/archive/community.applications-${version}-x86_64-1.txz

