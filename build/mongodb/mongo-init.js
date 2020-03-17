db.auth('app_access', 'MongoDB12345')

db = db.getSiblingDB('fabop_directory')

db.createUser({
  user: 'app_access',
  pwd: 'MongoDB12345',
  roles: [
    {
      role: 'readWrite',
      db: 'fabop_directory',
    },
  ],
});
