---
paths:
  - 'app/Concerns/**'
---

# Concerns

## Shared validation rule traits
Reusable validation rule sets (password, profile) live as traits in app/Concerns/. Consume them in both Fortify actions and Volt components via `use SomeValidationRules;`.
