# Maroc explore API , For explorers , Add iterinaries and destination 
# => Check the documentation below   
        https://documenter.getpostman.com/view/44570907/2sBXigLYru


# Use these examples for testing 
# MarocExplore API — Usage Examples
> All protected routes require `Authorization: Bearer <token>` header.

---

## Auth

### POST /api/v1/register
```json
{
    "name": "test",
    "email": "test@test.test",
    "password": "Password123?",
    "password_confirmation": "Password123?"
}
```
> Password must contain at least 1 uppercase letter, 1 number, and 1 symbol.

### POST /api/v1/login
```json
{
    "email": "test@test.test",
    "password": "Password123?"
}
```

### POST /api/v1/logout
No body needed. Adds the current token to the blacklist immediately.

---

## Itineraries

### POST /api/v1/iterinaries — Create itinerary with destinations
```json
{
  "title": "3 Days Exploring Marrakech and Atlas",
  "category": "mountain",
  "duration": "3 days",
  "image": "https://example.com/images/marrakech-trip.jpg",
  "destination": "Marrakech",
  "destinations": [
    {
      "name": "Jemaa el-Fna",
      "lieu_logement": "Riad Yasmine",
      "image": "https://example.com/images/jemaa.jpg"
    },
    {
      "name": "Imlil Village",
      "lieu_logement": "Atlas Mountain Lodge",
      "image": "https://example.com/images/imlil.jpg"
    }
  ]
}
```

### POST /api/v1/iterinaries/{id}/destinations — Add a destination to an itinerary
```json
{
  "name": "Ouarzazate",
  "lieu_logement": "Kasbah Hotel"
}
```

---

## Places  *(scoped to a destination)*

### GET /api/v1/destinations/{destination_id}/places
Returns all places linked to the destination.

### POST /api/v1/destinations/{destination_id}/places
```json
{
  "name": "Jardin Majorelle",
  "description": "Iconic cobalt-blue garden designed by Jacques Majorelle, now owned by Yves Saint Laurent."
}
```

### GET /api/v1/destinations/{destination_id}/places/{place_id}
Returns a single place.

### PUT /api/v1/destinations/{destination_id}/places/{place_id}
```json
{
  "name": "Jardin Majorelle",
  "description": "Updated description."
}
```

### DELETE /api/v1/destinations/{destination_id}/places/{place_id}
Returns `{ "message": "Place supprimée" }`.

---

## Stays  *(scoped to a destination)*

### GET /api/v1/destinations/{destination_id}/stays
Returns all stays linked to the destination.

### POST /api/v1/destinations/{destination_id}/stays
```json
{
  "name": "Riad Yasmine",
  "address": "18 Derb Sidi Ahmed Ou Moussa, Marrakech",
  "description": "Charming riad with rooftop pool in the heart of the medina."
}
```

### GET /api/v1/destinations/{destination_id}/stays/{stay_id}
Returns a single stay.

### PUT /api/v1/destinations/{destination_id}/stays/{stay_id}
```json
{
  "address": "Updated address",
  "description": "Updated description."
}
```

### DELETE /api/v1/destinations/{destination_id}/stays/{stay_id}
Returns `{ "message": "Logement supprimé" }`.

---

## Dishes  *(scoped to a destination)*

### GET /api/v1/destinations/{destination_id}/dishes
Returns all dishes linked to the destination.

### POST /api/v1/destinations/{destination_id}/dishes
```json
{
  "name": "Pastilla au Pigeon",
  "restaurant": "Le Foundouk",
  "description": "Flaky pastry filled with spiced pigeon, almonds, and cinnamon — a Marrakchi classic."
}
```

### GET /api/v1/destinations/{destination_id}/dishes/{dish_id}
Returns a single dish.

### PUT /api/v1/destinations/{destination_id}/dishes/{dish_id}
```json
{
  "restaurant": "Dar Yacout",
  "description": "Updated restaurant and description."
}
```

### DELETE /api/v1/destinations/{destination_id}/dishes/{dish_id}
Returns `{ "message": "Plat supprimé" }`.