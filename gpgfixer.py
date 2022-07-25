from sys import stdin

def stdin_until_eof():
    output = ""
    for line in stdin:
        output = output + line;
    return output

def reformatInput():
    gpgstring = stdin_until_eof()

    if "-----BEGIN PGP MESSAGE-----" in gpgstring:
        mode = "message"

    if "-----BEGIN PGP PUBLIC KEY BLOCK-----" in gpgstring:
        mode = "pubkey"

    if (mode == "message"):
        gpgstring = gpgstring.replace("-----BEGIN PGP MESSAGE-----","")
        gpgstring = gpgstring.replace("-----END PGP MESSAGE-----","")
    if (mode == "pubkey"):
        gpgstring = gpgstring.replace("-----BEGIN PGP PUBLIC KEY BLOCK-----","")
        gpgstring = gpgstring.replace("-----END PGP PUBLIC KEY BLOCK-----","")
    
    outstring = ""
    x = 0
    for char in gpgstring:
        if (char == " "):
            continue
        if (char == "\n"):
            continue

        outstring = outstring + char
        x = x + 1
        if (x == 65):
            outstring = outstring + "\n"
            x = 0

    if (mode == "message"):
        print("-----BEGIN PGP MESSAGE-----")
    if (mode == "pubkey"):
        print("-----BEGIN PGP PUBLIC KEY BLOCK-----")
    print(outstring)
    if (mode == "message"):
        print("-----END PGP MESSAGE-----")
    if (mode == "pubkey"):
        print("-----END PGP PUBLIC KEY BLOCK-----")

reformatInput()
