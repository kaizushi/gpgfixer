GPG Fixer
=========

This fixes formatting for ASCII armor GPG messages. Sometimes GPG formatting such as newlines and such are truncated. This basically filters formatting away and then regenerates the base64 block of formatting with newlines at the correct spaces. It supports public keys and encrypted messages at this point.

To use just run the script in your terminal...

`python3 gpgfixer.py`

Then paste in the garbled message and press CTRL-D to send EOF. The EOF must be at the beginning of a line. After this the script will output a correctly formatted message or public key.

Written by Kaizushi for the benefit of all.
