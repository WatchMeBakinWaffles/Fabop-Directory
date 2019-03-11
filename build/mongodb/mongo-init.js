db.auth('app_access', '3zzHK7bVrchfZrRy33a9P2VpVq2QnzwGjcAYFNtDQFcYZ3nyFpwDFCWEELaXfEug')

db = db.getSiblingDB('fabop_directory')

db.createUser({
  user: 'app_access',
  pwd: '3zzHK7bVrchfZrRy33a9P2VpVq2QnzwGjcAYFNtDQFcYZ3nyFpwDFCWEELaXfEug',
  roles: [
    {
      role: 'readWrite',
      db: 'fabop_directory',
    },
  ],
});