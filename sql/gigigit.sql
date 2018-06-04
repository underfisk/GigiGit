CREATE TABLE github_tokens(
  id int(11) unsigned not null primary key auto_increment,
  user_id int(11) unsigned not null,
  git_token text not null,
  git_scopes text not null,
  generated_date timestamp not null
);