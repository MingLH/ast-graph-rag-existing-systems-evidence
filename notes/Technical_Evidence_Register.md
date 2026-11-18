---
title: Existing Systems Technical Evidence Register
project: AST-Graph RAG | FYP
version: "1.0"
date: 2026-11-14
status: Evidence-file verified; interpretation limited to tested scope
systems: [CodeGraph, Mapin, Nexus]
tags: [FYP, literature-review, existing-systems, evidence, reproducibility]
---

# Existing Systems Technical Evidence Register — v1.0

**FYP:** *AST-Graph RAG: An Abstract Syntax Tree and Knowledge Graph Context Retrieval Pipeline for Laravel Codebases*  
**Purpose:** Maintain the primary, auditable record of independent experiments on **CodeGraph**, **Mapin** and **Nexus** for Chapter 2 (and later methodology/design decisions). This is an **evidence register**, not a draft literature review and not a benchmark claiming superiority over existing systems.  
**Evidence cutoff:** 14 November 2026 (MYT).  
**Relative links:** Paths are written for a register saved as `notes/Existing_Systems_Technical_Evidence_Register_v1.0.md` inside `<RESEARCH_ROOT>/`.

> **Verification declaration.** The uploaded archive `ast-graph-rag-existing-systems-evidence.tar.gz` was independently opened. All **33 archived experimental evidence files** matched the **33 SHA-256 hashes** recorded in `evidence/test-results/existing-systems-manifest.txt` (**0 mismatches**). The archive contains **54 regular files**, including PHP experiment scripts, fixture sources, three SQLite graph databases, logs, output summaries and repository notes. Selected SQLite databases and the recorded raw JSON were inspected. No tests were rerun during this archival verification. Repository revisions are evidenced by the user's `git rev-parse` inventory and the archived `notes/repository-snapshots.txt`; the git repositories themselves were **not** included in the archive, so uncommitted worktree state has not been verified. See [manifest](../evidence/test-results/existing-systems-manifest.txt) and §10.

## 1. Research boundaries and evidence rules

### 1.1 Approved FYP scope

**Fixed evaluation repository:** **BookStack v26.09.1**, targeting **Laravel 12**, pinned at `31d94dd8b98e3334dc01b66bed6d514719a2f85d`. Selected PHP application files, HTTP routes and Blade templates are in scope; configuration files may be consulted when necessary to resolve source references. The **524-file experiments** reported here inspected the PHP files under `app/` and `routes/` only. They did **not** establish processing coverage for all Blade template files.

**Five intended statically resolvable relationships:**

- **R1:** route → controller method.
- **R2:** controller → application collaborator (service/repository/query class).
- **R3:** controller method → collaborator method.
- **R4:** controller/collaborator method → Eloquent model.
- **R5:** controller method → Blade view.

**Three planned retrieval configurations:** A = fixed-size vector retrieval; B = syntax-aware vector retrieval; C = syntax-aware graph-assisted retrieval. **RQ1** concerns extraction reliability; **RQ2** concerns retrieval effectiveness; **RQ3** concerns presenting static relationships together with generated answers. The user's planned extraction metrics include precision, recall and F1; retrieval effectiveness is to be evaluated with a verified ~24-question BookStack benchmark and controlled comparisons.

### 1.2 Evidence status labels

| Label | Meaning |
|---|---|
| **OBS** | Reproduced/observed in a saved executed experiment, within its stated input/configuration. |
| **INS** | Established from inspected implementation, but **not** independently executed for that exact capability in these experiments. |
| **PAR** | Only a narrower, related or configuration-dependent form was observed; not equivalent to the full FYP relationship. |
| **NE** | Not established by the available evidence; **not** the same as unsupported. |
| **HYP** | Research hypothesis/design intention; neither a proven novelty claim nor demonstrated advantage. |

**Do not equate** passing developer tests with correctness on BookStack, a raw edge count with accurate relationships, a nonempty chunk count with parser correctness, a cosine-like score from synthetic vectors with real semantic relevance, or a feature absent in source-only mode with a feature absent from the entire system.

### 1.3 Evidence provenance and reproducibility

All three investigations used separate original snapshots and runnable laboratories. The user's inventory recorded:

| Source or laboratory | Revision |
|---|---|
| `existing-systems/codegraph-core` and `runtime-labs/codegraph-core` | `0fd5b9a86939efdaa7f71ef462a4e1c47c064e21` |
| `existing-systems/codegraph-laravel` and `runtime-labs/codegraph-laravel` | `543b5ba320653e3206aebcff27c22ffda095c1d9` |
| `existing-systems/mapin`, `runtime-labs/mapin`, `runtime-labs/mapin-l12` | `9ae585e9b1b025d298ba709c55969152ea37bc76` |
| `existing-systems/nexus` and `runtime-labs/nexus` | `f305875dbec95e81f53d1e921e1bd10cd3ac056c` |
| `<BOOKSTACK_ROOT>` | `31d94dd8b98e3334dc01b66bed6d514719a2f85d` |

Archived [repository snapshot notes](repository-snapshots.txt) also record commit timestamps and descriptions for the four upstream repositories. These correspond to the revisions reported in the user's terminal inventory. No `git status` snapshot or copy of the full repositories was packaged.

## 2. CodeGraph: findings and provenance

**Role in review:** A structural PHP code-graph extractor with a Laravel-specific companion plugin. Relevant primarily to **RQ1**, particularly target resolution, class ownership and handling of framework-specific syntax.

### 2.1 Developer suites (OBS)

| Archived evidence | Observed result |
|---|---|
| [Core PHPUnit](../evidence/test-results/codegraph-core-phpunit.txt) | **283 passed, 673 assertions**, PHPUnit 13.4.6, PHP 8.5.0. |
| [Laravel companion PHPUnit](../evidence/test-results/codegraph-laravel-phpunit.txt) | **16 passed, 78 assertions**, PHPUnit 13.4.6, PHP 8.5.0. |
| **Combined reported count** | **299 tests, 751 assertions**, across two **separate** suites. |

The PHP development environment was not a controlled Laravel 12 BookStack application boot. Passing both suites does not mean all five FYP relationship categories were extracted correctly.

### 2.2 Independent extractor and full-pipeline fixtures (OBS)

**Input sources:** [extractor cases](../fixtures/codegraph-extractor-pilot.php), [full-pipeline runner](../fixtures/codegraph-full-pipeline.php) and [six-file fixture](../fixtures/codegraph-full-pipeline/).  
**Results:** [independent extractor JSON](../evidence/test-results/codegraph-laravel-independent-pilot.json) and [full-pipeline JSON](../evidence/test-results/codegraph-full-pipeline.json).

**Important accuracy correction from direct archive review:**

- **Direct `Route::get()` is detected** by the independent `RouteExtractor` fixture (`R01_direct_route` and `R03_imported_controller` each emitted an edge). It would be **incorrect** to claim that CodeGraph cannot recognize any unqualified `Route::get()` call.
- `R02_resource_route` (`Route::resource()`) emitted **no route edge** in the independent fixture.
- In the full-pipeline six-file fixture, the graph contained **12 symbols, 7 edges and 12 chunks**. The direct GET route yielded `UserController@show`, a **string-style route target**, while the fixture's resource registration did not yield a corresponding route edge. Thus route detection was observed, but **normalization to a verified controller-method graph node** was not established in that test.
- The controller's `$this->service->find()` call yielded `?::find` rather than `App\Services\UserService::find`. The fixture's `view('users.show', ...)` appeared as a generic call to `view`, **not** a demonstrated R5 edge.
- The Eloquent `hasMany` relationship was **misattributed to `App\Models\First`** although the method appears in `Second::posts()`; this is a confirmed controlled fixture error.
- The unrelated `$this->builder->bind('A','B')` in `RuleBuilder` generated a **spurious `service_binding`** (`App\Services\RuleBuilder → B`). This is a confirmed controlled false-positive case.
- The `morphTo()` case returned no independent extractor edge. Its target is inherently ambiguous without additional information; a missing edge here should **not automatically** be scored as incorrect under a statically-resolvable-only scope.

These observations are **specific, reproducible failure cases**, not a complete precision/recall audit.

### 2.3 BookStack PHP source scan (OBS)

**Runner:** [codegraph-bookstack-scan.php](../fixtures/codegraph-bookstack-scan.php), with the [diagnostics runner](../fixtures/codegraph-bookstack-diagnostics.php). Both index `app` and `routes` in an **in-memory SQLite graph**; they do **not** boot the BookStack application or connect to its database.

| Quantity | Verified result |
|---|---:|
| PHP files scanned | **524** |
| Files failed / reported errors | **0 / 0** |
| Symbols | **2,725** |
| Chunks | **2,725** |
| Edges | **11,178** |
| `calls` edges | 9,737 |
| `route` edges | 340 |
| `instantiates` edges | 618 |
| `extends` edges | 265 |
| `implements` edges | 82 |
| `service_binding` edges | 6 |

**Raw evidence:** [BookStack summary](../evidence/test-results/codegraph-bookstack-summary.json), [diagnostics](../evidence/test-results/codegraph-bookstack-diagnostics.json).

**Diagnostics, not ground-truth error totals:**

| Diagnostic | Observed count | Interpretation |
|---|---:|---|
| `calls` targets with `?::` prefix | **6,239** | Unresolved target representation; **not** 6,239 verified incorrect edges. |
| `calls` targets ending `.php` | **263** | Suspicious target format, requiring source-level validation. |
| Calls targeted at `app/Config/view.php` | **132** | Strong sign of false call-target attribution for `view()`; the sampled `ConfirmEmailController::show` call points there. |
| `route` target values containing `@` | **340** | All reported route edges remained `Controller@method`-style strings; **not** independently resolved route→method-node links. |

The source scan demonstrates considerable graph output but also illustrates target-resolution ambiguity. **Do not infer relative quality from comparing CodeGraph's 11,178 edges with Mapin's 9,628; the schemas and counting rules differ.**

### 2.4 CodeGraph conclusion

**Supported:** Parsing/indexing, multiple structural edge kinds, some route and model constructs.  
**Observed limitations:** Resource-route omission in the selected case; unresolved collaborator method call; wrong class attribution for a model relation; false-positive binding; unnormalized route targets; suspicious `view()` target mapping.  
**Not demonstrated:** Complete R1–R5 accuracy, robust Laravel 12 runtime resolution, comparative RAG effectiveness or LLM answer quality.

## 3. Mapin: findings and provenance

**Source repository:** `Nexvia-Solutions/mapin` at revision `9ae585e9b1b025d298ba709c55969152ea37bc76`.  
**Role in review:** Laravel-aware structural graph extraction, including a meaningful distinction between **source-only scanning** and a **registered Laravel router/container**. Relevant mainly to **RQ1** and architectural trade-offs in source resolution.

### 3.1 Developer tests and dependency configurations (OBS)

| Evidence | Recorded environment / result |
|---|---|
| [Initial Composer installation](../evidence/test-results/mapin-composer-install.txt) and [test log](../evidence/test-results/mapin-pest.txt) | PHP 8.5.0, Laravel **13.42.0**, **194 tests passed, 573 assertions**. |
| [Laravel 12 Composer installation](../evidence/test-results/mapin-l12-composer-install.txt) and [test log](../evidence/test-results/mapin-l12-pest.txt) | PHP 8.5.0, Laravel **12.72.0**, **194 tests passed, 575 assertions**. |

Both runs are independent developer-suite evidence, not two separate Laravel application benchmarks. The differing assertion counts should be preserved rather than averaged or combined.

### 3.2 BookStack source-only scan (OBS)

**Input and mode:** [mapin-bookstack-static.php](../fixtures/mapin-bookstack-static.php) scanned `app` + `routes`, with `vendorPaths: []`, no application boot (`ExtractionContext(..., booted: false)`). It wrote to an isolated [SQLite database](../evidence/test-results/mapin-bookstack-static.sqlite). The [JSON summary](../evidence/test-results/mapin-bookstack-static.json) and **read-only verification of the archived SQLite database** agree on the following counts:

| Quantity | Verified result |
|---|---:|
| Files | **524** |
| Graph nodes | **3,595** |
| Graph edges | **9,628** |
| Unresolved references | **1,037** |
| Report warnings | **0** |
| Recorded build-report duration | **5.165 s** |

**Edge-type distribution:** `calls` **5,781**; `declares` **2,752**; `instantiates` **614**; `extends` **176**; `renders` **124**; `relates` **71**; `implements` **47**; `maps_table` **37**; `dispatches` **17**; `touches_table` **7**; `resolves` **2**. These sum to **9,628** and were corroborated against the stored SQLite edge table.

**Unresolved types:** `calls` **1,033**, `instantiates` **3**, `view` **1**. These are extractor classifications, not an independently adjudicated error set.

**Concrete, independently inspectable examples:**

- `ConfirmEmailController::showAwaiting` → `LoginService::getLastLoginAttemptUser`, `calls`, resolution `typed_property`, recorded confidence 1.
- `ConfirmEmailController::show` → `view:auth.register-confirm`, `renders`, with the logical Blade view name retained.
- `Comment` → `JointPermission`, `relates`, represented as a **class-to-class relationship**.
- **No `routes_to` or `binds` edges** were in the archived **source-only** BookStack SQLite graph. This is not evidence that Mapin cannot produce these edge kinds under a framework-aware configuration.

### 3.3 Six-file controlled source fixture (OBS)

**Evidence:** [build report](../evidence/test-results/mapin-controlled-fixture-build.json), [actual edge rows](../evidence/test-results/mapin-controlled-fixture-edges.json), [SQLite graph](../evidence/test-results/mapin-controlled-fixture.sqlite). The fixture PHP sources are under [fixtures/codegraph-full-pipeline/](../fixtures/codegraph-full-pipeline/) and were reused across tools, preserving consistent inputs.

**Results:** **6 files**, **19 nodes**, **17 edges**, **1 unresolved** and **0 warnings**.

**Precise relationship examples:**

- `UserController::show` → `UserService::find`, `calls`, `typed_property` resolution.
- `UserController::show` → `view:users.show`, `renders`.
- `Second` → `Post`, `relates`, with metadata `{"relation":"hasMany","method":"posts"}`. **This is a class→model edge with method provenance in metadata, not an explicit method→model edge as R4 defines.**
- Unqualified `Route::get()` and `Route::resource()` were represented as calls to the facade in source-only mode, **not** registered `routes_to` edges.
- `$this->builder->bind('A','B')` remained a single unresolved method call rather than becoming a spurious container binding.

### 3.4 Framework-aware Laravel 12 fixture (OBS)

**Script:** [mapin-l12-framework-pilot.php](../fixtures/mapin-l12-framework-pilot.php), using Laravel 12 `Router` and `Container` objects with the [controlled framework fixture](../fixtures/mapin-l12-framework-fixture/). The script registers routes and a binding without dispatching web requests. It **does not boot the full BookStack application**.

**Evidence:** [JSON results](../evidence/test-results/mapin-l12-framework-pilot.json) and [SQLite output](../evidence/test-results/mapin-l12-framework-pilot.sqlite).

| Quantity | Result |
|---|---:|
| Source files | **7** |
| Nodes / edges | **36 / 30** |
| Unresolved references | **1** |
| Reported warnings | **0** |
| Laravel routes registered | **9** |
| `routes_to` edges in archived graph | **3** |
| `binds` edges | **1** |
| Explicit asserted checks | **5/5 true** |

Five positive checks covered direct GET routing, resource `show` routing, greeting route resolution, abstract→concrete binding, and `GreetingController::show` → `FriendlyGreeter::greet` through container resolution. **Do not present the 9 registered routes as 9 successfully mapped route edges**; the actual graph has three `routes_to` edges in this fixture.

### 3.5 Mapin conclusion

**Supported:** Source-only analysis of methods, views and Eloquent relations; selected registered-router, container and collaborator-method resolution when explicit Laravel objects are supplied.  
**Observed boundaries:** Source-only mode omits registered-router/container edges; ambiguous references remain unresolved; the demonstrated Eloquent edge has **class-level endpoints**, not the exact method-level R4 schema.  
**Not demonstrated:** Full BookStack boot/indexing, complete relationship accuracy, vector retrieval, generation or comparative RAG effectiveness.

## 4. Nexus: findings and provenance

**Source repository:** `nick-nds/nexus`, commit `f305875dbec95e81f53d1e921e1bd10cd3ac056c`, Python distribution `nexus-php` **1.4.1**.  
**Role in review:** The closest inspected architectural comparator for **RQ2**, combining Laravel reflection, typed graphs, PHP syntax-aware chunking, LanceDB vector search and graph-aware query tools.

**Source-inspected design, separate from archived execution evidence:** Nexus's PHP extractor emits a validated Laravel reflection document; the Python graph builder produces typed nodes and edges; `PhpChunker` produces declaration-based chunks; optional LSP enrichment supports `calls` edges; `semantic_search` performs vector selection, graph-based annotation and node-kind reweighting. The archive does **not** include the full upstream Nexus source clone; the analysis was performed on the pinned source during the earlier technical investigation. Reference files at that commit include:

- [`nexus/core/chunking/php_chunker.py`](https://github.com/nick-nds/nexus/blob/f305875dbec95e81f53d1e921e1bd10cd3ac056c/nexus/core/chunking/php_chunker.py)
- [`nexus/core/graph/builder.py`](https://github.com/nick-nds/nexus/blob/f305875dbec95e81f53d1e921e1bd10cd3ac056c/nexus/core/graph/builder.py)
- [`nexus/core/graph/types.py`](https://github.com/nick-nds/nexus/blob/f305875dbec95e81f53d1e921e1bd10cd3ac056c/nexus/core/graph/types.py)
- [`nexus/core/query/tools/semantic_search.py`](https://github.com/nick-nds/nexus/blob/f305875dbec95e81f53d1e921e1bd10cd3ac056c/nexus/core/query/tools/semantic_search.py)

### 4.1 Developer test and environment verification (OBS)

| Evidence | Result |
|---|---|
| [Initial PHP PHPUnit](../evidence/test-results/nexus-php-phpunit.txt) | **163 tests, 400 assertions, 1 failure, 2 deprecations**, before test-environment repair. |
| [Targeted PHP retest](../evidence/test-results/nexus-php-targeted-retest.txt) | **1 passed, 21 assertions, 2 deprecations**. |
| [Full PHP retest](../evidence/test-results/nexus-php-phpunit-retest.txt) | **163 passed, 414 assertions, 2 deprecations**. |
| [Python installation](../evidence/test-results/nexus-python-install.txt) | Editable `nexus-php` **1.4.1** installation succeeded, including `fastembed`, Tree-sitter, LanceDB, pytest. |
| [Python dependencies](../evidence/test-results/nexus-python-freeze.txt) | Installed Python distribution versions archived. `pip check` and imports were reported successful in the terminal. |
| [Python pytest](../evidence/test-results/nexus-python-pytest.txt) | **1,242 passed, 29 skipped, 0 failures**, 46.14 s. |

**PHP failure explanation:** The original failure was `ExtractPackageCommandTest::test_extracts_sample_fixture_with_kind_package`, which expected a fixture description but received `null`. In the isolated runtime, a missing Testbench vendor-path symlink was created; targeted and full reruns passed. The original failing log is retained. This is **consistent with** a laboratory path-resolution issue; the archive does not independently capture the symlink command or prove the upstream software is bug-free.

**Conditional Python skips (29 total):** package integration/E2E **18**, model-downloading FastEmbed tests **6**, unavailable LSP server **1**, and Ollama integration **4**. Skips are neither passes nor failures, and they identify untested integration paths.

**Composer security audit:** [audit JSON](../evidence/test-results/nexus-php-composer-audit.json) records **four advisory entries** for **one package**, `laravel/framework` **11.57.1** selected by the PHP test laboratory. The entries concern **three distinct conditions**: debug-page XSS (low), temporary signed URL path confusion (medium), and CRLF injection in the default email rule (high; represented by two records). These are dependency advisories in the testing environment, **not** evidence of an exploitable Nexus-specific flaw. Never interpret passing tests as clearing the advisories.

### 4.2 Controlled PHP syntax-aware chunking (OBS)

**Evidence:** [pilot](../evidence/test-results/nexus-chunking-pilot.json), based on the shared PHP fixture plus two selected BookStack source files. **6 files**, **24 chunks**, **7/7 checks true**. Demonstrated correct `UserController::show` and `Second::posts` attribution, repeatable chunk IDs on unchanged inputs, exact method source text and separate class/method chunks. The fixture route file generated zero chunks under the standalone PHP chunker.

The chunker emits **synthesized declaration summaries** for class headers; method chunks preserve source spans. The parser/chunker experiment does **not** test route reflection extraction, which is a different component.

### 4.3 Full selected BookStack PHP chunking scan (OBS)

**Evidence:** [BookStack scan JSON](../evidence/test-results/nexus-bookstack-chunking-scan.json).

| Quantity | Verified result |
|---|---:|
| PHP files inspected (`app/**/*.php`, `routes/**/*.php`) | **524** |
| Total chunks | **2,752** |
| Method chunks | **2,244** |
| Class headers | **479** |
| Interface headers | **15** |
| Trait headers | **5** |
| Enum headers | **3** |
| Functions | **6** |
| Files producing no chunks | **21** (**19 config**, **2 route**) |
| Method-source mismatches | **0 of 2,244 checked** |
| Duplicate chunk IDs within run | **0** |
| Median / maximum chunks per file | **4 / 37** |
| Measured scan and validation duration | **1.098 s** |

Only class-/method-/function-like declaration chunks were emitted by this standalone chunker. **No-chunk output does not imply a parser failure**; there was no separate AST syntax-error census. It also does not prove that those files are ignored in the complete Nexus reflection or graph pipeline. Timing excludes embeddings, full graph indexing, BookStack boot and generative answering.

### 4.4 Graph construction and structural query pilot (OBS)

**Evidence:** [graph-query output](../evidence/test-results/nexus-graph-query-pilot.json). Input was Nexus's **bundled `demoapp.json` reflection fixture**, not BookStack. The tool built/persisted the graph to a temporary SQLite database and queried routes and a controller.

| Quantity | Result |
|---|---:|
| Nodes | **594** |
| Edges | **527** |
| Route nodes | **23** |
| `routes_to` edges | **19** |
| Method nodes / `part_of` edges | **214 / 214** |
| Builder warnings | **12** |
| Explicit checks | **8/8 true** |

The graph represented `route:GET:/api/v1/customers` → `method:App\Http\Controllers\CustomersController::index` and other controller routes. The 19 routes-to-method edges matched the number of **controller-action routes in this fixture**, not a gold-standard completeness estimate. No `calls` or `returns_view` edges appeared in this fixture output; their nonappearance here does not show the implementation lacks those edge kinds.

**Raw-log format caveat:** Although named `.json`, `nexus-graph-query-pilot.json` begins with two **debug log lines** followed by JSON, so the entire file is **not strict JSON**. Preserve it unchanged as raw evidence; parsers should consume the JSON section after the first `{` or use a separately generated normalized copy.

### 4.5 Graph-annotated vector search (OBS)

**Evidence:** [semantic graph pilot](../evidence/test-results/nexus-semantic-graph-pilot.txt). Used three **controlled synthetic 4-dimensional vectors**, a fake deterministic embedder and the demoapp graph; **8/8 checks true**. The indexed candidates and observed results were:

| Candidate | Raw vector similarity | Reranked score | Structural annotation |
|---|---:|---:|---|
| `CustomersController::index` | **1.00** | **1.20** | Containing controller; `GET /api/v1/customers` |
| `CustomersController` class | **0.80** | **0.84** | No related route in output |
| `CustomersController::show` | **0.00** | **0.00** | Containing controller; customer-detail route |

The pilot deliberately set `min_vector_score=0.0` so a zero-score row remained visible. Scores are **artificial controls**, not real retrieval precision, embedding relevance, or answer quality. The source-inspected `semantic_search` multiplies similarity by node-kind weights (e.g., method ×1.20; controller ×1.05), adds owning-class/route annotations and deduplicates by node ID.

**Discriminating top-1 experiment:** [top-1 contrast](../evidence/test-results/nexus-semantic-top1-contrast.txt) stored three vectors but asked for only **one** vector candidate. **9/9 checks true**:

- Only `method:App\Http\Controllers\CustomersController::index` was returned as a search hit.
- `GET /api/v1/customers` existed in the graph and appeared as an **annotation**, not as a second result or added code-chunk candidate.
- This independently supports the narrow description: **in the tested `semantic_search` tool, graph information enriches and reranks vector-selected candidates; it does not expand the returned candidate set with neighboring graph nodes**.

**Important boundary:** Nexus has **separate structural traversal tools** (`trace_route`, `get_request_flow`, etc.). The top-1 result is **not** evidence that Nexus has no graph traversal. It is a **retrieval-tool-specific** contrast with the proposed bounded graph-expansion method.

### 4.6 Nexus conclusion

**Supported:** Tree-sitter-based PHP chunk extraction, typed reflection-to-graph construction on a bundled fixture, SQLite persistence, structural queries, controlled LanceDB vector retrieval and graph annotations.  
**Not demonstrated:** Full BookStack reflection extraction/graph completion; actual LSP-enabled method-call coverage; meaningful live embedding relevance; production LLM output; five-category accuracy against a verified BookStack gold standard.  
**FYP relevance:** Strongest architectural comparator to the planned RQ2 investigation, but **not** a directly measured RAG baseline.

## 5. Direct comparison against the FYP's five relationships

**Read the cells as evidence descriptions, not yes/no product feature ratings.** The FYP's node/edge endpoints are more specific than some systems' implemented edges.

| FYP relationship | CodeGraph | Mapin | Nexus |
|---|---|---|---|
| **R1 route → controller method** | **PAR/OBS:** Direct `Route::get` produces a route edge, but target stays `Controller@method`; fixture `Route::resource` yielded none. BookStack has 340 string-style route targets. | **OBS (framework fixture):** Direct/resource route mappings demonstrated by 3 `routes_to` edges and 5 positive checks. Source-only BookStack produced 0 such edges. | **OBS (bundled reflection):** 19 `routes_to` edges to method nodes. **NE** for BookStack boot/extraction. |
| **R2 controller → collaborator class** | **NE for direct R2:** Bindings are a different relationship; a false-positive binding was observed. | **PAR:** Typed-property call and framework container binding show resolution components; no independently established dedicated controller→collaborator edge in this scope. | **PAR/INS:** `bound_to` encodes binding→concrete, not a direct controller→collaborator edge. |
| **R3 controller method → collaborator method** | **PAR/OBS:** The controlled `UserController::show` call ended at unresolved `?::find`, although generic `calls` edges were emitted. | **OBS:** `UserController::show` → `UserService::find`, and framework-aware `GreetingController::show` → `FriendlyGreeter::greet`. | **INS/NE:** `calls` edge type and optional LSP enrichment exist; no LSP-enabled execution was performed in this investigation. |
| **R4 controller/collaborator method → Eloquent model** | **PAR/OBS:** A `hasMany` class→model relation was emitted with **incorrect** source class `First` instead of `Second`. Not a demonstrated method→model edge. | **PAR/OBS:** Correct `Second` → `Post` **class-level** `relates` edge, with `posts` method recorded in metadata. Not an exact method→model edge. | **NE:** This exact FYP category was not independently evaluated. |
| **R5 controller method → Blade view** | **NE/diagnostic:** `view()` represented as generic call; BookStack `view()` calls mapped spuriously to `app/Config/view.php`. No correct method→logical-view edge demonstrated. | **OBS:** `renders` links methods to logical view nodes, including `users.show` and `auth.register-confirm`; 124 `renders` edges in BookStack source-only scan. | **INS/NE:** `returns_view` exists as a graph edge kind in pinned source; no corresponding edge was produced in the evaluated bundled fixture. |

**Implication:** The direct endpoint-level mapping of R2 and R4 requires particular design care; a dependency-injection binding or a class-level Eloquent association is not automatically the same as the FYP's specified graph relation. Any accuracy evaluation must define its **own gold-standard entity and relation schema** before scoring comparisons.

## 6. Cross-system quantitative context, not a leaderboard

| Measurement | CodeGraph | Mapin | Nexus |
|---|---:|---:|---:|
| Developer-suite result | **299 tests / 751 assertions** across core + Laravel adapter | **194 tests / 575 assertions** on Laravel 12 (separate 194/573 on Laravel 13) | **163 PHP tests / 414 assertions** after repair; **1,242 Python passed**, 29 skipped |
| Selected BookStack PHP files | **524** | **524** | **524** (chunker only) |
| BookStack graph nodes / symbols | **2,725 symbols** | **3,595 nodes** | **Not measured** |
| BookStack graph edges | **11,178** | **9,628** | **Not measured** |
| BookStack chunks | **2,725** (system-defined) | **Not reported equivalently** | **2,752** (syntax-aware PHP chunks) |
| BookStack unresolved references | **6,239 `?::` calls** within 9,737 call edges | **1,037 unresolved** across several types | **Not measured** |
| Actual vector retrieval | **Not tested** | **Not tested** | **OBS:** controlled synthetic vectors, not real semantic relevance |
| Full BookStack application indexing | **No application boot** | **No application boot** | **Not performed** |
| Verified LLM answer-quality comparison | **None** | **None** | **None** |

**Comparison caveats:** Different tool purposes, relationship schemas, chunk definitions, execution conditions, test-suite coverage and output counting rules prevent using these numbers to rank correctness, efficiency or accuracy. Mapin's reported 5.165 s includes its source-only build; Nexus's 1.098 s measures chunking plus custom validation; CodeGraph's corresponding full-build timing was not consistently captured. Do **not** compare these as identical performance benchmarks.

## 7. Preliminary synthesis linked to research questions

### RQ1 — Extraction reliability

The controlled fixtures show that **route syntax**, **method ownership**, **typed collaborator resolution**, **container bindings** and **Eloquent relationships** require different resolution rules. CodeGraph exhibited a false-positive `bind`, incorrect class attribution and unresolved method call; Mapin resolved selected typed calls and framework registrations but retained class-level Eloquent relationship endpoints. These motivate a clear relationship schema, static-resolvability boundary and verified precision/recall/F1 evaluation on the FYP's target repository.

### RQ2 — Retrieval effectiveness

Nexus demonstrates that syntax-aware chunks, typed graphs and vector search already coexist in a Laravel-oriented system. Its tested `semantic_search` enriches vector hits with structural annotations and node-kind weighting. **Hypothesis for AST-Graph RAG:** bounded traversal of the five supported Laravel relationship types to retrieve **additional related source chunks** may improve relevance/coverage against a vector-only starting set. This is **not a proven benefit**, and must be tested under a controlled retrieval budget and common benchmark against configurations A and B.

### RQ3 — Presentation of relationships

The systems show useful structured relationship information, but the archive contains **no controlled user study or comparative assessment of generated visual explanations**. The FYP's plan to present explanations and Mermaid diagrams should be framed as a prototype capability requiring functional verification; no claim of superior comprehension or usability is supported by these tests. The separately proposed SUS participant study is **not mandatory** under the supervisor-approved scope.

### Research-gap statement (provisional, pending Academic Literature Review Round 2)

> The reviewed systems demonstrate practical PHP/Laravel graph extraction, method-oriented chunking and graph-annotated code retrieval, but these investigations do not establish, under a common controlled benchmark, whether retrieval **expanded through a fixed set of statically resolvable Laravel relationships** improves architectural-question retrieval over syntax-aware vector search alone, or how reliably that precise five-relationship schema can be extracted from BookStack. AST-Graph RAG proposes to investigate those questions empirically.

**Do not claim** that no prior academic research has evaluated graph-expanded code retrieval. That claim remains open until Round 2 systematically examines academic literature on program graphs, AST chunking, repository-level RAG and evaluation. The technical findings motivate the hypothesis but do not independently establish novelty.

## 8. Verification exceptions, provenance boundaries and safeguards

1. **Archive hash verification is complete.** All 33 manifest entries matched the archived bytes. This is **integrity**, not proof of the scientific correctness of every experimental assertion.
2. **Mapin SQLite files were inspected read-only**, and reported counts for `files`, `nodes`, `edges`, `unresolved` and selected edge kinds matched the archived JSON summaries.
3. **Nexus standalone Python experiments were submitted through Bash here-documents.** Their raw result files were archived, but the exact standalone `.py` experiment scripts were **not saved inside `fixtures/`**. A future script reconstruction should be explicitly labeled a reconstruction from the conversation, not the byte-exact original input. No rerun is required merely to preserve the results.
4. **Malformed-extension nuance:** `nexus-graph-query-pilot.json` begins with debug lines, and therefore is **raw mixed text + JSON**, despite its `.json` extension. Do not silently alter the hashed original file. The two Nexus semantic pilot outputs use `.txt` and also include debug lines.
5. **CodeGraph direct route nuance:** Direct route extraction was observed; **resource handling and normalization** were the limitations. Avoid the blanket claim that unqualified `Route::get()` is unsupported.
6. **Mapin Eloquent endpoint nuance:** The positive `Second → Post` relation is **class-level**, with `posts` metadata. Treating it as a direct method→model edge would overstate the tested capability.
7. **Mapin source-only/runtime distinction:** Zero `routes_to` in the BookStack static graph does not contradict positive registered-router cases on the Laravel 12 fixture.
8. **Nexus semantic-search limitation is tool-specific:** Its separate structural tools support graph traversal; the top-1 test only demonstrates that `semantic_search` did not **add neighboring nodes as search candidates** in that configuration.
9. **Versioning and worktree status:** Source SHA matches were verified from the provided git inventory, but the archived package does not include all upstream repository bytes or a `git status --porcelain` proof. The Mapin databases are execution artifacts, not independent source revisions.
10. **No retrieval benchmark metrics:** None of these systems was judged using the FYP's ~24 verified questions, extraction gold-standard precision/recall/F1, comparable retrieval metrics or independent LLM answer-quality judgments.
11. **No runtime-tracing evidence:** The FYP intentionally excludes runtime execution tracing. The controlled Laravel router/container fixture is a registered-framework inspection, not a runtime request trace.
12. **Licensing/security:** Before code reuse or dissemination, inspect each upstream license at the pinned version. Nexus's source was previously inspected as BSL 1.1; this archive does not contain its full license. Keep environment Composer audit concerns separate from tool feature claims.

## 9. Recommended use in Chapter 2 and next steps

**Phase A (now complete):** Freeze all existing-system experiment evidence, pinned revisions and analytical qualifications in this register.  
**Phase B (next):** Academic Literature Review Round 2; seek primary scholarship on (i) PHP/Laravel static call/route/DI resolution, (ii) AST-aware chunking, (iii) graph-expansion retrieval for repository code questions, (iv) verified benchmark design, and (v) retrieval and answer-quality evaluation.  
**Phase C:** Use both scholarly studies and this register to write balanced CodeGraph, Mapin and Nexus reviews, followed by a narrowly worded comparison and research-gap synthesis.  
**Phase D:** Reuse the relation-level observations as **design risks and test cases**, not as calibration data or a ready-made gold standard for the FYP evaluation.

**No further existing-system experiment is required for the initial Chapter 2 review** unless Round 2 reveals a specific unresolved claim that would change the study's framing.

## 10. Archived evidence file manifest

This section records the **exact SHA-256 fingerprints** already independently rechecked against the uploaded archive. All paths below are relative to the research workspace root; see the original [manifest](../evidence/test-results/existing-systems-manifest.txt).

| System | Evidence path | SHA-256 |
|---|---|---|
| CodeGraph | `evidence/test-results/codegraph-bookstack-diagnostics.json` | `2f79b5aeec33eaa3897df0687dc427a1f5e815c4ee9e3d232bdd15883d2430e1` |
| CodeGraph | `evidence/test-results/codegraph-bookstack-summary.json` | `ea528724d249cad15de6db83bc3ea45a9bff59bc97f78c76756d32fa9320773a` |
| CodeGraph | `evidence/test-results/codegraph-core-phpunit.txt` | `db576a7b2ccb307d23ebc28a847b770929779579734687ed66c6f41126558b1a` |
| CodeGraph | `evidence/test-results/codegraph-full-pipeline.json` | `0a676d5e949b65364181b4360918a3f9ce53280ba7711ec30018c2a149135b53` |
| CodeGraph | `evidence/test-results/codegraph-laravel-independent-pilot.json` | `ca0d2c54499165f4fa0c677c495a71c2c9bf7fca02d7048a66a58462aa4ec579` |
| CodeGraph | `evidence/test-results/codegraph-laravel-phpunit.txt` | `618f658d6d19f91c4b86de4f0d9aae24e5dacaf792d9d426460fb1da21bd79ec` |
| Mapin | `evidence/test-results/mapin-bookstack-static.json` | `42d5e7de8fd74f821d0626b14faad473348418097b185ff9968db3cf699a8df8` |
| Mapin | `evidence/test-results/mapin-bookstack-static.sqlite` | `5bdce47436ffca5afbee45550ed24d561541d2155ab8bdedb5399b05b86913d1` |
| Mapin | `evidence/test-results/mapin-composer-dry-run.txt` | `53bb9850b0b63985907e1d2fe83013fefd2894b850c2c34bf92fdc42fe014d25` |
| Mapin | `evidence/test-results/mapin-composer-install.txt` | `e641ab8c9b6cee72cdfb91f8119764919329cc286b4702805d42f737a7ef40a3` |
| Mapin | `evidence/test-results/mapin-controlled-fixture-build.json` | `7fb37e3d36f0d3224de0510a1538708d1dbd1193ae926d0b40fc111cb801ffc4` |
| Mapin | `evidence/test-results/mapin-controlled-fixture-edges.json` | `7fecb2aa4de31197e1b0cffffc17e0807a4fa1325e4ecf05dcbf28dbc3f09805` |
| Mapin | `evidence/test-results/mapin-controlled-fixture.sqlite` | `43780cc092863b1bd6aba4a69f4e009326f7411a0f7d48bbd695cbad55a87648` |
| Mapin | `evidence/test-results/mapin-l12-composer-dry-run.txt` | `3d5e7768ac90a0b830454426e4b674314c88b2c27dff1a9f1363355558c8fb31` |
| Mapin | `evidence/test-results/mapin-l12-composer-install.txt` | `8cdeca0eb02e3913c846a0df6b2076bc9c6bcf11655fc9a07e346c30e3368887` |
| Mapin | `evidence/test-results/mapin-l12-framework-pilot.json` | `d9ced4054425d5ad72cb66109fe7709c2960cbd646aaa0da2bea05fc5d152426` |
| Mapin | `evidence/test-results/mapin-l12-framework-pilot.sqlite` | `8d71c6b6a73242161eb0a50e5d2601854c818c031c8c325c551d2bedea0a115a` |
| Mapin | `evidence/test-results/mapin-l12-pest.txt` | `d2537f6d666c180207595d37fc425824472b0156cfd6d83acaeb097b37a0caea` |
| Mapin | `evidence/test-results/mapin-pest.txt` | `2f5d22df50a3bec61e67392e007b4c41293fbeec20f7b98237f147f90f3b90b3` |
| Nexus | `evidence/test-results/nexus-bookstack-chunking-scan.json` | `644fe442056746dd7609e26ebc4b726f1aecc697a3693030ca7423b3ac12e74a` |
| Nexus | `evidence/test-results/nexus-chunking-pilot.json` | `22ace430d49684ac5eaf0baf410b2d8c39842f72253c086dab54e0d76b37dc70` |
| Nexus | `evidence/test-results/nexus-graph-query-pilot.json` | `b5cc93b01384c24f79a278d627ba0ae5d7223ec2b33baa51529e8b03a8df4195` |
| Nexus | `evidence/test-results/nexus-php-composer-audit.json` | `b0579ed028d4ec4f39fb35aaee07c90756b5810f0dac087b45473cfa1cda2355` |
| Nexus | `evidence/test-results/nexus-php-composer-dry-run.txt` | `41e40a9104d3ee5dfbf83b93d45e12d5deeb6f55db09e2ef64082e5115df1f21` |
| Nexus | `evidence/test-results/nexus-php-composer-install.txt` | `b5e507ab426921b7eefcb81dfe419e66a593c8e0096e1788ce3ae02c8567b297` |
| Nexus | `evidence/test-results/nexus-php-phpunit-retest.txt` | `d22315a7f5c36322d51e9edd97a01d321d90a0f80fd64763d845156569fb0f25` |
| Nexus | `evidence/test-results/nexus-php-phpunit.txt` | `03d6bc26b0d8540e4bd6b51cbee6f7f3beac441f9711b962f93edf5cf89c4c90` |
| Nexus | `evidence/test-results/nexus-php-targeted-retest.txt` | `e508037c9ada08eb112a0a02989aa127a7aacaf6eda631bdd40cc65f40a97854` |
| Nexus | `evidence/test-results/nexus-python-freeze.txt` | `0056eebeebba506de6719a2faaf2830aa22e4e6bc7bb5b8761383268b3be0665` |
| Nexus | `evidence/test-results/nexus-python-install.txt` | `3b52b8cfb64dee5581a136023dc3622274446721852f2769a67ae0f8d016f707` |
| Nexus | `evidence/test-results/nexus-python-pytest.txt` | `922b6f4f82da48e27cef045fd1963c2b04a8c8c126258c77e5eeb0213bd33be8` |
| Nexus | `evidence/test-results/nexus-semantic-graph-pilot.txt` | `607fb4d4b0282cccca9a601996a1263201f8938409cf322eee67baeecdb4fc60` |
| Nexus | `evidence/test-results/nexus-semantic-top1-contrast.txt` | `d94b70347df0b02f3957639cd8d5ea1f6076afc4301a2357da5cdd441152499c` |

**Integrity result:** **33/33** archived file hashes matched the manifest; **0 discrepancies**. This register is an interpretation layer. The raw inputs, test logs, JSON results and SQLite data should remain immutable.
