---
id: coursesets
title: Enrollment sets and rules
sidebar_label: Enrollment sets and rules
---

## Enrollment sets and rules

### Concept
A registration set is a framework for courses that have common rules for registration. Stud.IP 3.0 initially provides a number of different rules, but here we will describe how you can implement such a rule yourself.



### Structure of a registration rule
All submission rules are located in the `lib/admissionrules` folder. There is a folder for each rule type, which typically contains the class definition of the rule (`ruletype.class.php`, SQL statements that must be executed when the rule is (de)installed and templates for configuring and briefly displaying the rule.

In the following, the example `NightAdmission` will be developed, a rule that only allows registration between 10 pm and 6 am.

#### Saving and loading the data
The rules of type `NightAdmission` should all be saved in a separate `nightadmissions` table in the database. As this rule type has no other attributes apart from the standard info text, this table looks like this:

```sql
CREATE TABLE `nightadmissions` (
    `rule_id` VARCHAR(32) NOT NULL,
    `message` TEXT NOT NULL,
    `mkdate` INT NOT NULL,
    `chdate` INT NOT NULL,
    PRIMARY KEY (`rule_id`);
```

If this rule type is completely removed from the system, the following SQL statement is sufficient to clean it up:

```sql
DROP TABLE `nightadmissions`;
DELETE FROM `courseset_rule` WHERE `type`='NightAdmission';
```

#### Rule definition
We create a file `NightAdmission.class.php`, which inherits from the already existing class `AdmissionRule`. As we only need to take the current time into account, this class does not need any additional attributes of its own. We only define with which other submission rule types this rule can be combined (namely all standard rules except time-controlled and completely blocked submission).

We also have to implement some standard methods in order to realize loading and saving in our own tables.

```php
<?php
class NightAdmission extends AdmissionRule {

    /**
     * Standard constructor
     */
    public function __construct($ruleId=*, $courseSetId = *)
    {
        parent::__construct($ruleId, $courseSetId);
        $this->default_message = _("You can only register at night between 10 p.m. and 6 a.m.");
        if ($ruleId) {
            // Rule already exists, load data.
            $this->load();
        } else {
            // Create new ID.
            $this->id = $this->generateId('nightadmissions');
        }
        return $this;
    }

    /**
     * Delete current rule from the database.
     */
    public function delete() {
        parent::delete();
        $stmt = DBManager::get()->prepare("DELETE FROM `nightadmissions` WHERE `rule_id`=?");
        $stmt->execute(array($this->id));
    }

    /**
     * Description text for this rule type, is displayed when a new
     * rule is to be added to a logon set.
     */
    public static function getDescription() {
        return _("This rule only allows logins at night between 10 p.m. and 6 a.m.");
    }

    /**
     * Name for this rule type, is displayed when a new rule is to be added to a
     * Logon set is to be added.
     */
    public static function getName() {
        return _("Nightly login");
    }

    /**
     * Fetches the template for displaying the configuration of this rule
     * (configuration.php, stored in the templates subfolder). For our
     * example, we only need the standard template, as there is nothing of our own *
     * to configure for this rule type.
     */
    public function getTemplate() {
        $tpl = $GLOBALS['template_factory']->open('admission/rules/configure');
        $tpl->set_attribute('rule', $this);
        return $tpl->render();
    }

    /**
     * Loads the rule from the database.
     */
    public function load() {
        $rule = DBManager::get()->fetch("SELECT * FROM `nightadmissions` WHERE `rule_id`=? LIMIT 1", array($this->id));
        $this->message = $rule['message'];
        return $this;
    }

    /**
     * This function checks whether the given user is allowed to register for the given
     * event, i.e. whether the rule applies.
     * An error message is returned if registration is not possible.
     * possible.
     */
    public function ruleApplies($userId, $courseId) {
        $failed = array();
        $now = mktime();
        // Time between 6 am and 10 pm => no registration allowed.
        if (date('H', $now) < 22 && date('H', $now) >= 6) {
            $failed[] = $this->default_message;
        }
        return $failed;
    }

    /**
     * Saves the current rule in the database.
     */
    public function store() {
        $stmt = DBManager::get()->prepare("INSERT INTO `nightadmissions`
            (`rule_id`, `message`, `mkdate`, `chdate`)
            VALUES
            (:id, :message, UNIX_TIMESTAMP(), UNIX_TIMESTAMP())
            ON DUPLICATE KEY
            UPDATE `message`=VALUES(`message`), `chdate`=VALUES(`chdate`)");
        $stmt->execute(array('id' => $this->id, 'message' => $this->message));
        return $this;
    }

}
```

The two most important methods are `ruleApplies` and `getTemplate`.

The first method specifies the behavior of the rule, i.e. when and under what conditions a login can be successful. In principle, any database queries or other functions can be called here.

The template defines the GUI for configuring the respective rule. By default, only one text field is offered, which can contain a text that appears on the event page before registration. If you want to set additional values, checkboxes or other things here, you have to write a [Flexi-Template](FlexiTemplates) yourself.

There can also be an info template that is only used to display the rule in normal prose text.

### Summary
To install this example rule in Stud.IP, it is sufficient to create a folder `nightadmissions` in `lib/admissionrules`, copy the above class `Nightadmission.class.php` into it and execute the necessary SQL statements. As no separate template is required, no subfolder `templates` is necessary. The rule can then be activated in the **global configuration** under **Subscription rules**. The existing rules with which the new rule can be combined must then be set in the same place under **Rule compatibility**.



## Distribution algorithm
The algorithm that distributes the places for the events in a registration set can also be freely implemented. An algorithm is already supplied as standard.

To create a new algorithm, it is sufficient to implement the existing `AdmissionAlgorithm` interface, which is basically just a `run()` method that executes the algorithm.
