<?php

use Behat\Behat\Context\Step\Given as Given;

class behat_availablity_publicprivate extends behat_base {
    /**
     * Pauses the scenario until the user presses a key.
     * Useful when debugging a scenario.
     *
     * @Given /^I put a breakpoint$/
     */
    public function i_put_a_breakpoint() {

        fwrite(STDOUT,
                "\033[s \033[93m[Breakpoint] Press \033[1;93m[RETURN]\033[0;93m to continue...\033[0m");

        while (fgets(STDIN, 1024) == '') {
            // Intentionally empty.
        }

        fwrite(STDOUT, "\033[u");
        return;
    }
}