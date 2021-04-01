<div class="card grid-margin">
    <div class="card-body">
        <h4 class="card-title mb-3">Help Overview</h4>

        <article>
            <h5>First at Glance</h5>
            <p>
                Everything you need to know about E-purchasing vendor management system
                in <?= get_setting('company_name') ?>
                environment. Electronic purchasing (e-purchasing), automates and extends manual buying processes, from
                the creation of the requisition through to payment of the vendors.
            </p>

            <hr>

            <h5>Master Data</h5>
            <p>
                Master data contains everything you need to setup first kick of purchasing transaction. There are useful
                information regard record in master such requisition stats or member or related down or up.
            </p>

            <hr>

            <h5>Purchasing</h5>
            <p>
                The application help you manage records of purchasing that you need in future to predict or support your
                decision to selecting or eliminate vendors that you ever contact. First you need some master data, then
                you ready to making requisition with all related data regard master, system automatically sent vendors
                email, so they notice that you need something. Vendor who interest about your request making offer or
                quotation in specific requisition by replying Purchasing Admin email or by their account directly. After
                that, purchasing or manager would evaluate and consider by vendor track records to making decision which
                one to be selected and start to make purchase order to them. The selected vendor would receive email
                purchase order and try to deliver the goods or services, in the end Purchasing Admin would evaluated the
                goods or services is good enough by rating them with start from one to five.
            </p>

            <hr>

            <h5>Reporting</h5>
            <p>
                Decision support system is hard to build, need huge data set and mining as level as machine learning to
                give excellent recommendation, E-purchasing app not even close to those things but we try to report the
                data clear as possible, we present summary of purchasing history, vendor ratings and purchased items.
            </p>

        </article>
    </div>
</div>

<div class="card grid-margin">
    <div class="card-body">
        <h4 class="card-title mb-3">Glossary</h4>
        <ul>
            <li><strong>Role:</strong> group access that allow or restrict user to use purchasing application feature
            </li>
            <li><strong>User:</strong> account that allowed access application regarding their roles</li>
            <li><strong>Requester:</strong> user that able to making request of goods or service</li>
            <li><strong>Purchasing Admin:</strong> user that allow to manage and proceed request from user</li>
            <li><strong>Purchasing Manager:</strong> user that given permission to override all access and limitation of data restriction</li>
            <li><strong>Decision Maker:</strong> user that responsible to give recommendation and vendor selection</li>
            <li><strong>Data Validation:</strong> checking process to make sure the inputs is tion</li>
            <li><strong>Requisition:</strong> request of goods or service that applied by Requester to Purchasing Admin by Supervisor approval</li>
            <li><strong>Vendor:</strong> third party that provide goods or services</li>
            <li><strong>Vendor Selection:</strong> decision making by top manager to decide which and what vendors to be selected</li>
            <li><strong>Quotation / Offer:</strong> vendor purchase offer regarding Requester's requisition that managed by Purchasing Admin</li>
            <li><strong>Purchase Order (PO):</strong> document offer issued by Purchase Admin to a Vendor indicating specs and agreed prices for products or services</li>
            <li><strong>Data Revert:</strong> Undo requisition to specific state, in case data editing</li>
            <li><strong>Requisition Status:</strong> captured state in every step of requisition
                <ul>
                    <li><u>Pending:</u> first status when requisition is created</li>
                    <li><u>Approved:</u> request already acknowledged by their Supervisor or first status of
                        Supervisor's requisition
                    </li>
                    <li><u>Rejected:</u> request is rejected by Supervisor or Admin because insufficient information
                    </li>
                    <li><u>Cancelled:</u> cancelled request and allowed to be deleted</li>
                    <li><u>Listed:</u> Purchasing Admin already notify related vendors to making quotation</li>
                    <li><u>Ask for Selection:</u> Purchasing Admin notify decision maker to selecting vendor</li>
                    <li><u>Ready:</u> top manager already give decision via email</li>
                    <li><u>Selected:</u> Purchasing Admin proceed vendor selection by recommendation of decision maker
                    </li>
                    <li><u>In Process:</u> some of vendor offer at least one offer is ordered (PO has created)</li>
                    <li><u>Done:</u> all vendor offer is converted to purchase order</li>
                </ul>
            </li>
            <li><strong>Quotation Status:</strong> vendor offer data state
                <ul>
                    <li><u>On Review:</u> first applied status offer for requisition</li>
                    <li><u>Selected:</u> the offer is selected for PO creation</li>
                    <li><u>Unselected:</u> vendor offer that eliminate from candidate list after vendor selection</li>
                    <li><u>Ordered:</u> the offer is converted to Purchase order</li>
                    <li><u>Completed:</u> the order is received by Purchasing Admin</li>
                </ul>
            </li>
            <li><strong>Notification:</strong> message that sent to intention receiver by email and web push notification
            </li>
            <li><strong>Exported Data:</strong> external output such as PDF document and Spreadsheets</li>
        </ul>
    </div>
</div>


<div class="card grid-margin">
    <div class="card-body">
        <h4 class="card-title mb-3">Support</h4>
        <p class="mb-1">
            For further information and technical support, please contact bellow:
        </p>
        <ul class="list-unstyled">
            <li>
                <a href="mailto:<?= get_setting('email_support') ?>">
                    <?= get_setting('email_support') ?>
                </a>
            </li>
            <li>
                <a href="mailto:<?= get_setting('email_bug_report') ?>">
                    <?= get_setting('email_bug_report') ?>
                </a>
            </li>
        </ul>

        <p class="mb-1">
            All assets and code is copyright to <?= get_setting('company_name') ?> and dependencies or library is
            copyright respective owner.
        </p>
        <ul class="list-unstyled small text-muted mb-0">
            <li><?= get_setting('company_name') ?> Coder Team</li>
            <li>Finance & Purchasing</li>
        </ul>
    </div>
</div>