---
paths:
  - 'app/Actions/**'
---

# Actions

## Validater via Validator::make() in action classes
Action classes validate input with Validator::make($input, [...])->validate(). Use this instead of Form Requests or $request->validate().
