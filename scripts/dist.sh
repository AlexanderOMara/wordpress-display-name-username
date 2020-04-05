#!/bin/bash
set -o errexit
set -o nounset
set -o pipefail

if [ $# -ne 1 ]; then
	echo "Usage: $0 VERSION"
	exit 1
fi

__self="${BASH_SOURCE[0]}"
__dir="$(cd "$(dirname "${__self}")" > /dev/null && pwd)"
__file="${__dir}/$(basename "${__self}")"

cd "${__dir}/.."

dist='dist'
plugin_version="$1"
plugin_dir='display-name-username'
plugin_file="${plugin_dir}.php"
plugin_zip="${plugin_dir}.zip"
plugin_dist="${dist}/${plugin_dir}"

rm -rf "${dist}"
mkdir -p "${plugin_dist}"
plugin_src="$(<"${plugin_file}")"
echo "${plugin_src//0.0.0/${plugin_version}}" > "${plugin_dist}/${plugin_file}"
cp 'LICENSE.txt' "${plugin_dist}"
touch "${plugin_dist}/index.html"

cd "${dist}"
zip -r "${plugin_zip}" "${plugin_dir}"
rm -rf "${plugin_dir}"
