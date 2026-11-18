# Publication-copy redactions

**Why:** The original experimental archive contained absolute local filesystem paths revealing the researcher's computer account name and research directory. These are not necessary for interpreting the experimental results.

**Processing:** The original evidence archive was not modified. For the publication copy, literal path prefixes were replaced:

| Original prefix | Published placeholder |
|---|---|
| `/home/<local-user>/personal/ast-graph-rag-research` | `<RESEARCH_ROOT>` |
| `/home/<local-user>/personal/BookStack` | `<BOOKSTACK_ROOT>` |
| `~/personal/ast-graph-rag-research` | `<RESEARCH_ROOT>` |
| `~/personal/BookStack` | `<BOOKSTACK_ROOT>` |

All replacements were limited to text-file path prefixes; experiment count values, graph edges, stored relation types and actual source snippets were not intentionally changed. SQLite databases were not modified. This does not guarantee all possible private data has been detected; a final manual review should occur before publication.

**Publication files whose text changed:**

| File | Path-prefix occurrences replaced |
|---|---:|
| `notes/Technical_Evidence_Register.md` | 2 |
| `evidence/test-results/mapin-pest.txt` | 1 |
| `evidence/test-results/mapin-l12-pest.txt` | 1 |
| `evidence/test-results/mapin-bookstack-static.json` | 1 |
| `evidence/test-results/codegraph-bookstack-diagnostics.json` | 1 |
| `evidence/test-results/codegraph-core-phpunit.txt` | 1 |
| `evidence/test-results/nexus-php-phpunit-retest.txt` | 1 |
| `evidence/test-results/nexus-python-install.txt` | 1 |
| `evidence/test-results/nexus-chunking-pilot.json` | 6 |
| `evidence/test-results/codegraph-laravel-phpunit.txt` | 1 |
| `evidence/test-results/codegraph-bookstack-summary.json` | 1 |
| `evidence/test-results/nexus-php-targeted-retest.txt` | 1 |
| `evidence/test-results/nexus-php-phpunit.txt` | 2 |

**Hashes:** `notes/ORIGINAL_ARCHIVE_MANIFEST.txt` documents checksums for the original private output. Some publication files have different bytes due to these path transformations. Use `SHA256SUMS.txt` to verify the publication files. The original unredacted source archive is retained privately by the researcher.
