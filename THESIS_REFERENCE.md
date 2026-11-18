# Suggested minimal thesis cross-reference

After publishing this repository, add the **permanent GitHub release or commit URL** in these two places in the thesis. Substitute the actual final URL; do not leave this placeholder in a submitted report.

## Section 2.3 opening (add after existing introductory paragraph)

> The preliminary investigations were documented using controlled input fixtures, recorded outputs and repository revision information. Appendices A–C reproduce representative evidence, while the complete supplementary records and a claim-to-evidence index are available at **[INSERT VERIFIED PERMANENT GITHUB LINK]**. These observations reflect the stated experimental conditions and are not a comparative benchmark of the systems' overall accuracy.

## Appendices opening (add once, before Appendix A)

> **Supplementary technical evidence.** Appendices A–C present selected source-code and output excerpts from the existing-system investigations. The supporting fixtures, full recorded results, software revision information and a claim-to-evidence index are available in the companion evidence repository at **[INSERT VERIFIED PERMANENT GITHUB LINK]**. The public copies of certain logs have had local filesystem paths redacted for privacy; substantive test results are unchanged.

## Reference-list entry

Follow the academic reference format accepted by the faculty for an authored, versioned online research dataset. Use the student's verified authorship, publication year, repository title, version, publisher/host and fixed URL. Do **not** invent a DOI or pretend that a working-directory archive has already been published.

## Permalink guidance

Prefer a **GitHub release URL** (e.g. `https://github.com/OWNER/REPO/releases/tag/chapter2-evidence-v1.0`) and make sure the release points to a fixed commit. To link directly to an evidence item, use a GitHub file URL with that same commit SHA, not `main`.
