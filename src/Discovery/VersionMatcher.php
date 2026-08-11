<?php

declare(strict_types=1);

namespace CakePhpAgent\Discovery;

use Composer\Semver\Semver;
use Composer\Semver\VersionParser;

/**
 * Match Composer-style version constraints against installed versions or constraints.
 */
final class VersionMatcher
{
    private VersionParser $parser;

    public function __construct()
    {
        $this->parser = new VersionParser();
    }

    /**
     * Returns true when $versionOrConstraint satisfies $constraint.
     *
     * Installed lock versions are exact (e.g. "7.1.0"). When only a constraint
     * is available (no lock), we treat compatibility conservatively: the
     * candidate constraint must be a subset of / compatible with the required
     * constraint via Composer Semver ranges when possible; otherwise exact
     * string equality of normalized constraints is accepted as a weak match.
     */
    public function satisfies(string $versionOrConstraint, string $constraint): bool
    {
        $constraint = trim($constraint);
        $versionOrConstraint = trim($versionOrConstraint);

        if ($constraint === '' || $constraint === '*') {
            return true;
        }

        try {
            // Prefer treating the left side as a concrete version.
            $normalized = $this->parser->normalize($versionOrConstraint);

            return Semver::satisfies($normalized, $constraint);
        } catch (\UnexpectedValueException) {
            // Fall through — may be a constraint string from composer.json.
        }

        try {
            // Intersect candidate constraint with required constraint.
            // If intersection is empty, not satisfied.
            $required = $this->parser->parseConstraints($constraint);
            $candidate = $this->parser->parseConstraints($versionOrConstraint);

            return $required->matches($candidate);
        } catch (\UnexpectedValueException) {
            return false;
        }
    }

    public function isValidConstraint(string $constraint): bool
    {
        try {
            $this->parser->parseConstraints($constraint);

            return true;
        } catch (\UnexpectedValueException) {
            return false;
        }
    }
}
