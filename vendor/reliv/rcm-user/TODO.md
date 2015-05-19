# Future Features #

#### Composer Based Install ####

Story:
As a DevOps person
I should be able to install RcmUser using Composer
So that I may install in a standard way

#### More REST/JSON APIs ####

As DevOpts
I should have access to REST/JSON APIs
So that I may securely perform RcmUser actions VIA web clients

#### Addition Default Views ####

Story: 
As a user 
I should have access to edit my own user profile for certain fields based on configurable rules
So that I may update my profile data
    
 - User can edit own profile
 
As a user 
I should have access to a login page
So that i can log in to the site

As a user 
I should have be able to securely reset my password
So that I can get into my account if I forget or lose my password

- Standard email link to password reset

#### Admin Edit Profile Updates ####

Story:
As User when I access a user profile edit page
I should see a list of links or tabs to other profile data
So I can have quick access to all my user properties
    
 - User property links on user edit/profile pages:
 - Simple Interface to register profiles
    
#### Admin Role and Rule UI Updates ####

Story: 
As an ACL administrator 
I should be able to assign multiple privileges while creating rules 
So that the rules easier to administrate and view
 
 - ACL admin should be able to assign multiple privielges
 - Update rules entity to accept multi privileges (store array as cvs)
 - check privilege in is allowed as array
 - allow saving arrays in data mapper
 - fix AclResource->privileges to be object with json and to string and iteratable
 
Story: 
As a Administrator 
I should be able to paginate and filter Role and User lists on the Admin screens
So that I can quickly and efficiently edit Users and Roles
 
 - Admin User list should paginate and filter from server-side
 - Implement data mapper method for:
 - findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)

#### Security Updates ####

Story: 
As and Auditor 
I can access a log of actions performed on users and roles by administrators 
So that I track admin user changes
    
 - Implement logging audit trail for user creates and saves
 - might create event listeners or do at the service level
 - Logging of actions for security audits

#### DB and Query Optimization ####

 - Optimize mappers
 - Pagination for DataMappers

#### Translations ####

 - Result messages
 - Translation of other page content

#### Protection of default and special roles ####

As an ACL Administrator
I should not be able to delete super admin, guest or default roles
So that rules for default roles will not be removed

 - Should not be able to delete super admin, guest or default roles
 - OR should be able to restore them easily (even if they are deleted they are still there)

#### Full Deny rule support ####

Story: 
As a Role Administrator 
I should be able to create Deny rules so 
I may deny a role access

There may be inconsistent or undeterminable access for users with multiple roles.  
    
There needs be a way to determine which state the rule is using.

    Possible states
    
    - Explicit Allow (rule set for role)
    - Implicit Allow (inherited rule)
    - Explicit Deny (rule set for role)
    - Implicit Deny (inherited rule or no rule)
            
Active Directory uses the following rules, in our system this might be configurable:
 - Inherited Deny permissions do not prevent access to an object if the object has an explicit Allow permission entry.
 - Explicit permissions take precedence over inherited permissions, even inherited Deny permissions.

To fix:
 - Refactor AuthorizeService
 - Support getAccess() return type (allow, deny or none) in RcmUserACL extended
 - might look at moving some of AuthorizeService into RcmUserACL
 - may decouple from ZF2 ACL class

#### Guest User Identity features (maybe) ####

Story: 
As a consumer of RcmUser
I would like to have a guest user that functions just like a non-guest user 
So that guest user and non-gest user objects are seemless

 - Concept: There is alway a user object tied to session (even if not logged it).
Data may be tracked against the user object properties and may be synced to a user
on log in.
 - Guest user/guestIdentity 
 - if getIdentity is empty return guest?
    allow save updates in session so we can make updates to guest
 - On authenticate, we can try to merge guest user back into auth user
    if session id is the same and should have a flag (only do if requested)
 - Guest may have a time limit so we dont cross pollinate wrong guest
 - Clear both on log out?
 - Might use a event listeners (crud and auth)

#### ACL Exception handling ####
            
Story: 
As a user 
I should be denied access when a role or a resource is not defined withou an exception being thrown
So that my experince is seemless

 - May add suppresion of RcmException when a privilege of resource is not found
 - May add logging of this error

#### AclResourceService Refactor ####

Story: 
As Developer 
I need to refactor AclResourceService 
So that my code is clean, simple and efficient

 - Refactor AclResourceService
 - Use service manager for instantiation only (might be that way currently)
 - AclResourceService only need deal with ResourceProvider and AclResource objects
 - Build ResourceProvider populate method and take array on construct
 - Build AclResource populate method and take array on construct
 
#### Manage Orphaned Resources in for rules ####
 
 Story:
 As ACL
 I should remove rules if the resource no longer exists
 So that I do not retain unused data
 
 - Might do this on rules read
 - This is not a security issue
 