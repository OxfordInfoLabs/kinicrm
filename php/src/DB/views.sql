DROP VIEW IF EXISTS kcr_scope_objects;

CREATE VIEW kcr_scope_objects AS
    SELECT 'Organisation' scope, o.id, o.name FROM kcr_organisation o
    UNION
    SELECT 'Contact' scope, c.id, c.name FROM  kcr_contact c;
