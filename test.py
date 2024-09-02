import requests
import ssl
from requests.adapters import HTTPAdapter
from requests.packages.urllib3.poolmanager import PoolManager

# Custom adapter to enforce a specific TLS version
class TLSAdapter(HTTPAdapter):
    def __init__(self, tls_version=ssl.TLSVersion.TLSv1_2, **kwargs):
        self.tls_version = tls_version
        super().__init__(**kwargs)

    def init_poolmanager(self, *args, **kwargs):
        context = ssl.create_default_context()
        context.minimum_version = self.tls_version
        context.check_hostname = False  # Disable hostname checking
        kwargs['ssl_context'] = context
        return super().init_poolmanager(*args, **kwargs)

# Data to send in the POST request
postData = {
        "country_code":"+91",
        "mobile_number":"9354040454",
        "otp":"1828"
}

# Target URL
url = "http://185.193.19.48:9080/api/v1/verify-otp"

# Create a session and mount the custom adapter
session = requests.Session()
adapter = TLSAdapter(tls_version=ssl.TLSVersion.TLSv1_2)  # Use TLS 1.2; adjust if needed
session.mount('https://', adapter)

# Make the POST request
try:
    response = session.post(url, json=postData, verify=False, headers={"device-type":"Mobile", "description":"MOBILE WEB"})  # Temporarily disable SSL verification
    response.raise_for_status()  # Raise an exception for HTTP errors
    print(response.text)
except requests.exceptions.RequestException as e:
    print(f"An error occurred: {e}")
