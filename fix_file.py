path = r'd:\xampp\htdocs\hrm\application\models\Xin_model.php'
with open(path, 'rb') as f:
    lines = f.readlines()

# Verify start of duplication
# Line 1480 (index 1479) should be start of read_email_template
if b'function read_email_template' not in lines[1479]:
    print(f"Error: Line 1480 does not match. Found: {lines[1479]}")
    exit(1)

# Verify end of duplication
# Line 1666 (index 1665) should be empty line or start of next function
# The duplicate block ends after update_job_type_record (1664 })
# So 1665 might be blank.
# 1667 is the next read_email_template.
# Let's check 1667 (index 1666)
if b'function read_email_template' not in lines[1666]:
     print(f"Error: Line 1667 does not match next function. Found: {lines[1666]}")
     # It might be the comment line
     if b'// get single record' not in lines[1665]:
          print(f"Error: Line 1666 does not match comment. Found: {lines[1665]}")
          exit(1)

# Remove lines 1480 to 1666 (indices 1479 to 1665 exclusive? No, we want to remove up to 1665)
# We want to keep 1666 (comment) and 1667 (function)
# So remove 1479 up to 1665.
del lines[1479:1665]

with open(path, 'wb') as f:
    f.writelines(lines)

print("Successfully removed duplicated block.")
