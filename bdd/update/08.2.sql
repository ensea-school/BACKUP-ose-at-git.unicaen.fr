alter table utilisateur add PASSWORD_RESET_TOKEN varchar2(256) default null;
create unique index USER_PASSWORD_RESET_TOKEN_UN on utilisateur (PASSWORD_RESET_TOKEN);