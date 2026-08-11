---
name: configure-cache
description: Configure CakePHP Cache engines with keys, TTL, and invalidation.
---


# Configure cache

## Objective

Wire CakePHP Cache engines and application cache usage with clear keys/TTL and invalidation — after query ownership is sound.

## Use when

- Adding caching for expensive reads.
- Fixing cache stampedes/collisions/stale authz.

## Do not use when

- The real bug is N+1 — use `review-query-performance` / `diagnose-orm-query` first.

## Inputs to discover

1. Follow **`inspect-before-coding`**.
2. Inspect cache config engines/prefixes and existing `Cache::` usage.
3. Identify write paths that must invalidate.

## Workflow

1. Configure engine/prefix in config for the environment.
2. Use stable key schemes; set TTL deliberately.
3. Invalidate or version keys on writes.
4. Do not cache permissions without invalidation.
5. Add a test or manual check for hit/miss/invalidation.

## Framework decisions

- Performance rule: fix queries before reflexive Redis

## Anti-patterns

- Cache-as-band-aid for bad queries
- Laravel Cache facade inventions

## Validation

- Keys hit/miss as expected; stale data does not outlive writes.

## Completion criteria

- Cache config + call sites + invalidation path documented/tested.

