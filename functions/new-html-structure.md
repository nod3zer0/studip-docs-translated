---
id: new-html-structure
title: HTML structure
sidebar_label: HTML structure
---
Over the years, the basic HTML structure of a Stud.IP page has grown organically, so to speak, and in many places is not well suited to supporting accessibility. As of Stud.IP 5.3, Stud.IP will have a new structure for its pages that is more semantically oriented towards what each area is intended for.

# Basic structure of a Stud.IP page
````html
<html>
    <head>
    </head>
    <body>
        <div id="skip_link_navigation">Skiplinks</div>
        <header id="main-header">
            <div id="top-bar" role="banner">
                <div id="responsive-menu">Hamburger menu</div>
                <div id="site-title">Global title of the installation ("Stud.IP")</div>
                <div id="header-links">Dynamic links (e.g. to the developer chat), quick search, notifications, avatar menu</div>
            </div>
            <nav id="navigation-level-1">Main navigation</nav>
            <div id="current-page-structure">
                <nav id="navigation-level-2">Navigation of the current page</nav>
                <div id="page-title-container">Title of the current page</div>
            </div>
        </header>
        <aside id="sidebar">
            Sidebar with navigation (#navigation-level-3) and other widgets
        </aside>
        <main id="content-wrapper">
            The actual page content
        </main>
        <a id="scroll-to-top">
            Short link to jump to the top of the page
        </a>
        <footer id="main-footer">
            <div id="footer-info">
                Informative content ("Logged in as...", debug information if applicable)
            </div>
            <nav id="footer-navigation">
                Navigation points in the footer (legal notice, privacy policy, etc.)
            </nav>
        </footer>
    <body>
</html>
````

# Layout
A grid layout is used to display the page elements, which currently consists of 2 columns and 3 rows.
- The entire header forms row 1, which extends across the entire width of the page.
- The sidebar is in column 1, row 2.
- The content forms column 2, row 2.
- The footer also extends across the entire width of the page and takes up line 3.

# Transferring the old structure to the new one
The old page structure was transferred to the new structure as follows:

| Old element | New element |
|-------------------------|--------------------------|
| #layout_wrapper | *no longer exists* |
| #barBottomContainer | #top-bar |
| #barBottomLeft | #responsive-menu |
| #barTopFont | #site-title |
| #barBottomright | #header-links |
| #barTopAvatar | #avatar-menu-container |
| #notification_container | #notification-container |
| #header_avatar_menu | #avatar-menu |
| #flex-header | #navigation-level-1 |
| #barTopMenu | #navigation-level1-items |
| #barTopStudip | #top-logo |
| #layout_page | #current-page-structure |
| #layout_context_title | #context-title |
| .secondary-navigation | #navigation-level-2 |
| #page_title_container | #page-title-container |
| #current_page_title | #page-title |
| #layout_container | *no longer exists* |
| #layout-sidebar | #sidebar |
| section.sidebar | *no longer exists* |
| #layout_footer | #main-footer |
