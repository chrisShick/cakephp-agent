# Evaluation baselines

Offline evaluation platform for the curated `evaluations/` corpus.

Live model adapters are intentionally out of scope for the Phase 10 MVP. The runner loads fixtures deterministically, self-checks the heuristic scorer, and writes/compares baselines for regression reporting.

## CLI

```bash
php bin/cakephp-agent eval
php bin/cakephp-agent eval --category=anti-laravel
php bin/cakephp-agent eval --type=rejection --format=json
php bin/cakephp-agent eval --write-baseline=var/baselines/self-check.json
php bin/cakephp-agent eval --compare-baseline=var/baselines/self-check.json
```

### Options

| Flag | Meaning |
|---|---|
| `--category=` | Filter by category (repeatable / comma-separated) |
| `--type=` | Filter by evaluation type |
| `--id=` | Filter by evaluation id |
| `--extension=` | Keep core fixtures plus those requiring the extension |
| `--model=` / `--model-version=` | Labels stored in baseline metadata |
| `--format=text\|json` | Report format |
| `--write-baseline=PATH` | Write baseline JSON after the run |
| `--compare-baseline=PATH` | Compare current catalog/self-check to a baseline |

## Baseline document (`schema_version: 1`)

```json
{
  "schema_version": 1,
  "knowledge_version": "0.1.0",
  "model": "self-check",
  "model_version": "1",
  "created_at": "2026-08-11T03:00:00+00:00",
  "catalog": {
    "count": 58,
    "fingerprint": "sha256…",
    "by_category": {},
    "by_type": {},
    "ids": ["…"]
  },
  "summary": {
    "total": 58,
    "passed": 58,
    "failed": 0,
    "skipped": 0
  },
  "results": [
    { "id": "no-eloquent-scope", "status": "pass", "score": 1.0, "notes": [] }
  ]
}
```

- **knowledge_version** — package version that produced the baseline
- **model / model_version** — provider label (use `self-check` for offline plumbing)
- **catalog.fingerprint** — stable hash of `id:contentHash` pairs
- **results** — per-fixture self-check outcomes (future: live model scores)

## Regression compare

`--compare-baseline` reports:

- catalog fingerprint changes
- added/removed evaluation ids
- score regressions (`pass → fail`)

Exit code `1` when self-check fails or regressions are detected.

## Scoring (offline)

`HeuristicScorer` checks that a response contains expected `preferred` / `concepts` tokens and avoids `must_not` tokens. It is a plumbing aid, not a substitute for human or model judgment.
