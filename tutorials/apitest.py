#api key for openfec
#r6K96zZiE3CiSz10AhkCh0EGSpKNbxmDYD4osUAN

#https://api.open.fec.gov/v1/candidates/search/?sort_nulls_last=false&sort=name&api_key=DEMO_KEY&sort_null_only=false&page=1&sort_hide_null=false&per_page=20&name=Trump

import urllib.request, json

url = "https://api.open.fec.gov/v1/candidates/search/?sort_nulls_last=false&sort=name&api_key=DEMO_KEY&sort_null_only=false&page=1&sort_hide_null=false&per_page=20&name=Trump"

response = urllib.request.urlopen(url)

data = json.loads(response.read())

print(data)
