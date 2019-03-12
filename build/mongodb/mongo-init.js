db.auth('app_access', '<SET PASSWORD HERE>')

db = db.getSiblingDB('fabop_directory')

db.createUser({
  user: 'app_access',
  pwd: '<SET PASSWORD HERE>',
  roles: [
    {
      role: 'readWrite',
      db: 'fabop_directory',
    },
  ],
});