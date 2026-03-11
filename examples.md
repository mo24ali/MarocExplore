# respect the requirements for a better experience, advice from the developer mo

// add a user in the api/v1/register

{
    "name" : "test",
    "email" : "test@test.test",
    "password" : "Password123?",  // the password syntax must contains at least 1 capital letter and symbols and numbers 
    "password_confirmation" : "Password123?"

}

// test the api/v1/login


{
    "email" : "test@test.test",
    "password" : "Password123?"
}

// test the api/v1/logout

    this one apply the logout on the auth('api') => so it automatically add the access token of the current user in the blacklist even if the token isnt expired yet

// to test the add iterinaire with destinations

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