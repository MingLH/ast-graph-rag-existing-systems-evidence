#!/usr/bin/env python3
"""Validate the published research-evidence files against SHA256SUMS.txt."""
from pathlib import Path
import hashlib

base = Path(__file__).resolve().parent
manifest = base / 'SHA256SUMS.txt'
failed = []
checked = 0
for line in manifest.read_text(encoding='utf-8').splitlines():
    if not line.strip():
        continue
    expected, path = line.split('  ', 1)
    file = base / path
    found = hashlib.sha256(file.read_bytes()).hexdigest() if file.is_file() else 'MISSING'
    checked += 1
    if found != expected:
        failed.append(path)
print(f'Checked {checked} published files; mismatches: {len(failed)}')
for path in failed:
    print('MISMATCH:', path)
raise SystemExit(1 if failed else 0)
