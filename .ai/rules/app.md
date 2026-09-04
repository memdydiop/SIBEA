---
paths:
  - 'app/**'
---

# App

## Named routes for URL generation
Generate URLs with route('name') everywhere (PHP, Blade, Livewire). Avoid url('/path') and action().

## Action classes for business logic
Put business logic in action classes under app/Actions/ invoked via __invoke() (or a contract method), not in controllers. Avoid fat controllers.

## CarbonImmutable for all dates
Dates are immutable app-wide via Date::use(CarbonImmutable::class). Write date logic against immutable semantics; use now()/today() helpers.
