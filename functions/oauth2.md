---
title: OAuth2
---

# Setup

In order for Stud.IP to act as an authorization server in the context of OAuth2
key files must first be generated.

In order to check whether a Stud.IP installation is already supplied with
key files, this URL can be queried by
`root` authorized users can call up this URL:
`https://<STUD.IP-URL>/dispatch.php/admin/oauth2`

The system checks whether these files are available:

- `config/oauth2/private.key`
- `config/oauth2/public.key`
- `config/oauth2/encryption_key.php`

If this is not the case, these files can be created with the following call in the
directory of the Stud.IP installation.

```shell
cli/studip oauth2:keys
```

Check afterwards under the above URL that everything has been
has been set up correctly.

# Adding new OAuth2 clients

On the OAuth2 configuration page (`https://<STUD.IP-URL>/dispatch.php/admin/oauth2`) you have the option of
set up OAuth2 clients. To do this, click in the sidebar on
"Add OAuth2 client" in the sidebar. Now fill out the form that appears.

If you have successfully added a client, you will receive the
`client_id` and, if applicable, the `client_secret`. **Please note that
the `client_secret` is only displayed here once **.

# Manage OAuth2 clients

On the OAuth2 configuration page
(`https://<STUD.IP-URL>/dispatch.php/admin/oauth2`) to view and delete existing clients.
delete them. Changing the configuration is not intended. If you
want to change the details of an OAuth2 client, delete the existing
existing configuration and create a new one.

# Configuration of OAuth2 clients

After you have set up an OAuth2 client in your Stud.IP installation
installation, you now have the `client_id` and, if applicable, the
`client_secret`.

You also need the necessary URLs:

- Authorization URL: `https://<STUD.IP-URL>/dispatch.php/api/oauth2/authorize`
- Access Token URL: `https://<STUD.IP-URL>/dispatch.php/api/oauth2/token`

# What does the Stud.IP OAuth2 authorization server support?

## Grant Types

The following grant types are supported according to the specification:

- Authorization Code Grant
- Authorization Code Grant with PKCE
- Refresh Token Grant


## Scopes
Currently only one scope is provided: `api`. This scope allows
full access to all functions that are secured by OAuth2.

## PKCE procedure
When new clients are created, a query is made as to whether the client is
able to keep cryptographic secrets. This explicitly includes
explicitly includes all apps. In this case, the PKCE procedure
must be used: https://oauth.net/2/pkce/

# Clean up

Over time, revoked or expired tokens naturally accumulate in the database.
tokens accumulate in the database. Therefore, the following command
in the Stud.IP installation directory to remove these tokens.
remove these tokens:

```shell
cli/studip oauth2:purge
```
