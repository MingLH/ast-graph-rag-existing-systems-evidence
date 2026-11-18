# Existing-systems evidence for AST-Graph RAG (Chapter 2)

**Purpose:** Supplementary evidence for Section **2.3 Review of Existing Code Analysis and Retrieval Systems** of the FYP *AST-Graph RAG: An Abstract Syntax Tree and Knowledge Graph Context Retrieval Pipeline for Laravel Codebases*.

**Start here:** **[EVIDENCE_INDEX.md](EVIDENCE_INDEX.md)** maps each important claim in the Chapter 2 review directly to its supporting source/experiment and recorded result. It is organized by CodeGraph, Mapin and Nexus and is intended for examiners who do not need to read the entire evidence archive.

This is a research record, **not** the source code of the proposed AST-Graph RAG system and **not** a benchmark of which existing system is most accurate. These experiments used different software paths and conditions. A tool's absence of an edge in one fixture must not be interpreted as a statement about its entire functionality.

## Quick navigation

| Section 2.3 | Developer repositories | This archive's evidence |
|---|---|---|
| **2.3.1 CodeGraph** | [CodeGraph core](https://github.com/maarheeze/codegraph) and [Laravel extension](https://github.com/maarheeze/codegraph-laravel) | [Claims CG-01–CG-05](EVIDENCE_INDEX.md#codegraph-section-231) |
| **2.3.2 Mapin** | [Mapin](https://github.com/Nexvia-Solutions/mapin) | [Claims MP-01–MP-05](EVIDENCE_INDEX.md#mapin-section-232) |
| **2.3.3 Nexus** | [Nexus](https://github.com/nick-nds/nexus) | [Claims NX-01–NX-05](EVIDENCE_INDEX.md#nexus-section-233) |

## What's in the repository?

- [`fixtures/`](fixtures/): input PHP fixtures and scripts used for specific experimental runs.
- [`evidence/test-results/`](evidence/test-results/): captured JSON, log and SQLite outputs, including positive and negative findings. Files are archival outputs; they were **not rerun** when preparing this repository.
- [`notes/Technical_Evidence_Register.md`](notes/Technical_Evidence_Register.md): detailed experimental findings, caveats, revision inventory and source cross-references.
- [`notes/repository-snapshots.txt`](notes/repository-snapshots.txt): archived upstream revision inventory.
- [`notes/ORIGINAL_ARCHIVE_MANIFEST.txt`](notes/ORIGINAL_ARCHIVE_MANIFEST.txt): original checksums, preserved **only** as a historical inventory of the unredacted private archive.
- [`SHA256SUMS.txt`](SHA256SUMS.txt): checksums of the **publication copies** in this repository, reflecting documented path redactions.
- [`REDACTIONS.md`](REDACTIONS.md): description of privacy transformations made to the publication copy.
- [`THESIS_REFERENCE.md`](THESIS_REFERENCE.md): concise wording for referencing this archive from Chapter 2 and the report appendices.

## Versions and research boundary

The fixed BookStack source repository was **v26.09.1**, commit [`31d94dd8b98e3334dc01b66bed6d514719a2f85d`](https://github.com/BookStackApp/BookStack/commit/31d94dd8b98e3334dc01b66bed6d514719a2f85d). The **524-file** scans covered selected `app/` and `routes/` **PHP files**, not Blade templates and not full BookStack application execution.

| Investigated system | Recorded revision |
|---|---|
| [CodeGraph core](https://github.com/maarheeze/codegraph) | [`0fd5b9a`](https://github.com/maarheeze/codegraph/tree/0fd5b9a86939efdaa7f71ef462a4e1c47c064e21) |
| [CodeGraph Laravel](https://github.com/maarheeze/codegraph-laravel) | [`543b5ba`](https://github.com/maarheeze/codegraph-laravel/tree/543b5ba320653e3206aebcff27c22ffda095c1d9) |
| [Mapin](https://github.com/Nexvia-Solutions/mapin) | [`9ae585e`](https://github.com/Nexvia-Solutions/mapin/tree/9ae585e9b1b025d298ba709c55969152ea37bc76) |
| [Nexus](https://github.com/nick-nds/nexus) | [`f305875`](https://github.com/nick-nds/nexus/tree/f305875dbec95e81f53d1e921e1bd10cd3ac056c) |

These revisions were recorded in the original experiment inventory, not rechecked against fresh executions for this publication. The BookStack source itself is **not redistributed** in this repository; the archive contains derived experiment outputs and synthetic fixtures.

## How to examine the evidence

1. Open [EVIDENCE_INDEX.md](EVIDENCE_INDEX.md) and find the system and claim identifier relevant to the thesis paragraph.
2. Open the **input fixture/runner** and **recorded output** linked in that claim. Several claims have two links because both are needed to show what was expected versus observed.
3. Read the **test boundary**. Examples from small fixtures are not statistical measures of general extraction accuracy. In particular, Nexus's controlled vector tests used predefined embeddings, **not** a real semantic-retrieval benchmark.
4. For deeper provenance, consult [the evidence register](notes/Technical_Evidence_Register.md) and its cross-references. SQLite files are available for additional inspection but cannot be previewed directly in GitHub's text viewer.

The repository does not include full upstream clones, full BookStack execution environments, or a one-command reproduction workflow. To reproduce experiments, obtain the upstream versions, install appropriate dependencies and examine the archived runners. Some source- or environment-specific paths in logs have been replaced with placeholders; see [REDACTIONS.md](REDACTIONS.md).

## Integrity and limitations

The original archive's 33 checksummed evidence records were previously verified against their inventory. This publication copy is **not byte-for-byte identical** for files containing private absolute paths. The original source archive is held separately by the researcher. For the publication copy, use [`SHA256SUMS.txt`](SHA256SUMS.txt); do **not** use the original historical hashes to validate redacted files.

The appendix exhibits, test logs and graph counts establish **observations under specific conditions**. They are not an independently adjudicated ground-truth precision/recall study and cannot rank CodeGraph, Mapin and Nexus across different schemas and input settings.

## Third-party software

CodeGraph, Mapin, Nexus and BookStack are independently developed projects. Their source code and documentation remain governed by their respective licenses. This archive does not include vendor repositories; see the linked sources above. Before public publication, verify redistribution and institutional requirements, including the treatment of saved outputs derived from BookStack source analysis.
