# Change owner of /files directory to user cert
chown -R cert:cert /files
exec runuser -u cert "$@"
