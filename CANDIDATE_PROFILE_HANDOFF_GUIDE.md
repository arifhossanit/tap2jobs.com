# Candidate Profile Changes: Safe Handoff Guide

এই guide-এর উদ্দেশ্য হলো candidate-profile security changes নিরাপদে GitHub-এ রাখা, অফিসের computer-এ এনে পরীক্ষা করা এবং সবকিছু ঠিক থাকলে `home_page` branch-এ merge করা।

> **মূল নিয়ম:** পরীক্ষা শেষ হওয়ার আগে changes সরাসরি `home_page` branch-এ push করবেন না। প্রথমে আলাদা `candidate-profile-hardening` branch ব্যবহার করুন।

## বর্তমান অবস্থা

- Working branch: `home_page`
- `home_page` এবং `origin/home_page` একই commit-এ আছে।
- Candidate-profile changes এখনো uncommitted অবস্থায় আছে।
- Candidate regression suite সর্বশেষ run-এ `40 tests / 218 assertions` pass করেছে।
- Full suite-এ candidate-related failure নেই; আগে থেকে থাকা চারটি employer-only assertion failure এবং 128 MB cumulative test-memory সমস্যা আছে।

---

## ধাপ ১: বাসার computer থেকে নিরাপদ branch তৈরি

Project directory খুলুন:

```powershell
cd C:\Herd\tap2jobs.com
```

বর্তমান অবস্থা দেখুন:

```powershell
git status
git branch --show-current
```

Candidate changes-এর জন্য আলাদা branch তৈরি করুন:

```powershell
git switch -c candidate-profile-hardening
```

PHPUnit-এর generated cache commit থেকে বাদ দিন:

```powershell
git restore .phpunit.result.cache
```

কী কী পরিবর্তন হয়েছে review করুন:

```powershell
git status
git diff --stat
git diff --check
```

`git diff --check` কোনো output না দিলে whitespace check pass করেছে।

সব প্রয়োজনীয় file stage করুন:

```powershell
git add .
```

Staged files যাচাই করুন:

```powershell
git status
git diff --cached --stat
```

Commit তৈরি করুন:

```powershell
git commit -m "fix: harden candidate profile security and resume handling"
```

নতুন branch GitHub-এ push করুন:

```powershell
git push -u origin candidate-profile-hardening
```

Push সফল হয়েছে কি না দেখুন:

```powershell
git status
git log --oneline --decorate -3
```

Expected result: `candidate-profile-hardening` branch clean থাকবে এবং remote branch-এর সঙ্গে synced দেখাবে।

> **কেন stash নয়?** Git stash সাধারণভাবে অন্য computer-এ পাওয়া যায় না। Remote feature branch ব্যবহার করলে code GitHub-এ backup থাকবে এবং অফিস থেকে সহজে pull করা যাবে।

---

## ধাপ ২: অফিসের computer-এ branch নামানো

Project directory-তে যান:

```powershell
cd C:\Herd\tap2jobs.com
```

অফিসের computer-এ আগে কোনো অসমাপ্ত local changes আছে কি না দেখুন:

```powershell
git status
```

Uncommitted changes থাকলে সেগুলো না বুঝে overwrite বা discard করবেন না। আগে commit অথবা আলাদা backup branch-এ রাখুন।

Remote branch list update করুন:

```powershell
git fetch origin
```

Branchটি অফিসের computer-এ আগে না থাকলে:

```powershell
git switch --track origin/candidate-profile-hardening
```

Branchটি আগে থেকেই local-এ থাকলে:

```powershell
git switch candidate-profile-hardening
git pull --ff-only origin candidate-profile-hardening
```

সঠিক commit এসেছে কি না যাচাই করুন:

```powershell
git status
git log --oneline --decorate -5
```

---

## ধাপ ৩: Dependencies এবং environment প্রস্তুত করা

PHP dependencies sync করুন:

```powershell
composer install
```

Actual `.env` file-এ নিচের value থাকা recommended:

```dotenv
RESUME_DISK=private
```

Laravel configuration cache clear করুন:

```powershell
herd php artisan config:clear
```

Frontend dependencies এবং assets build করুন:

```powershell
npm install
npm run build
```

> এই changes-এর জন্য নতুন Composer বা NPM package যোগ করা হয়নি। Commands দুটি office environment-এর dependencies/assets current রাখার জন্য দেওয়া হয়েছে।

---

## ধাপ ৪: Migration চালানোর আগে backup

Migration চালানোর আগে নিচের জিনিসগুলোর backup নিন:

1. Local/development database
2. `storage/app/public`-এ থাকা existing candidate resumes
3. প্রয়োজন হলে সম্পূর্ণ `storage/app` directory

প্রথম migration existing candidate resume public disk থেকে private disk-এ move করে। এর `down()` method নিরাপত্তার কারণে resume আবার public disk-এ ফেরত নেয় না। তাই গুরুত্বপূর্ণ data-তে সরাসরি চালানোর আগে development copy-তে পরীক্ষা করুন।

---

## ধাপ ৫: Migration একে একে চালানো

Migrationগুলো নিচের order-এ চালান।

### ৫.১ Existing resumes private storage-এ নেওয়া

```powershell
herd php artisan migrate --path=database/migrations/2026_08_19_000001_move_candidate_resumes_to_private_disk.php
```

### ৫.২ Generated CV privacy preference যোগ করা

```powershell
herd php artisan migrate --path=database/migrations/2026_08_19_000002_add_cv_privacy_preference_to_candidates.php
```

### ৫.৩ Candidate unique ID repair এবং unique index যোগ করা

```powershell
herd php artisan migrate --path=database/migrations/2026_08_19_000003_make_candidate_unique_id_unique.php
```

Migration status যাচাই করুন:

```powershell
herd php artisan migrate:status
```

Production environment-এ প্রতিটি migrate command-এর শেষে `--force` যোগ করতে হবে। উদাহরণ:

```powershell
herd php artisan migrate --path=database/migrations/2026_08_19_000002_add_cv_privacy_preference_to_candidates.php --force
```

---

## ধাপ ৬: Automated tests

প্রথমে candidate-related regression suite চালান:

```powershell
herd php artisan test --do-not-cache-result tests\Unit --filter=Candidate
```

Expected result:

```text
Tests: 40 passed (218 assertions)
```

Feature tests আলাদাভাবে চালান:

```powershell
herd php artisan test --do-not-cache-result tests\Feature
```

শেষে চাইলে full suite চালান:

```powershell
herd php artisan test --do-not-cache-result
```

বর্তমান baseline অনুযায়ী full suite-এ candidate scope-এর বাইরে নিচের চারটি employer assertion failure দেখা যেতে পারে:

- Company logo validation/forwarding/media collection
- Employer account navigation offsets/hashes
- Company overview canonical data/safe links
- Required job editor এবং expiry HTML attributes

এক process-এ full suite চালালে PHP-এর 128 MB cumulative memory limit-ও hit করতে পারে। Candidate suite এবং Feature suite আলাদা process-এ pass করাই candidate changes যাচাইয়ের মূল signal।

---

## ধাপ ৭: Browser-এ manual verification

### Candidate account

- [ ] Candidate login সফল হচ্ছে
- [ ] Personal information দেখা এবং update করা যাচ্ছে
- [ ] Address update করা যাচ্ছে
- [ ] Employment, education এবং অন্যান্য section accordion কাজ করছে
- [ ] Invalid profile section clean `404` দিচ্ছে
- [ ] Profile image upload validation কাজ করছে

### Resume workflow

- [ ] PDF resume upload হচ্ছে
- [ ] JPG/PNG resume upload হচ্ছে
- [ ] Valid DOC/DOCX upload হচ্ছে
- [ ] Fake extension বা invalid file reject হচ্ছে
- [ ] Resume preview কাজ করছে
- [ ] Resume download কাজ করছে
- [ ] Candidate শুধু নিজের resume access/delete করতে পারছে
- [ ] Default resume select করা যাচ্ছে
- [ ] Application CV delete protection কাজ করছে

### Generated Application CV

- [ ] Application CV generate হচ্ছে
- [ ] Sensitive personal data default অবস্থায় hidden
- [ ] Privacy switch enable করলে allowed sensitive information include হচ্ছে
- [ ] Privacy setting পরিবর্তনের পরে CV regenerate হচ্ছে

### Employer/Admin access

- [ ] Logged-out user candidate details খুলতে পারছে না
- [ ] Employer/Admin active ও verified candidate দেখতে পারছে
- [ ] Inactive/unverified candidate clean `404` দিচ্ছে
- [ ] Employer শুধু নিজের job application-এর candidate resume download করতে পারছে
- [ ] অন্য model/collection-এর media resume endpoint দিয়ে access করা যাচ্ছে না

### XSS checks

- [ ] Candidate name-এ HTML/script দিলে executable output হচ্ছে না
- [ ] Profile text fields malicious event handler/script sanitize করছে

---

## ধাপ ৮: সব ঠিক থাকলে `home_page`-এ merge

প্রথমে latest shared branch নিন:

```powershell
git switch home_page
git pull --ff-only origin home_page
```

Colleague নতুন changes push করে থাকলে এই command সেগুলো আগে নামিয়ে আনবে।

Feature branch merge করুন:

```powershell
git merge --no-ff candidate-profile-hardening
```

Conflict হলে fileগুলো বুঝে resolve করুন। Conflict resolve করার পরে:

```powershell
git add .
git commit
```

Merge-এর পরে অন্তত candidate tests আবার চালান:

```powershell
herd php artisan test --do-not-cache-result tests\Unit --filter=Candidate
```

সব ঠিক থাকলে shared branch push করুন:

```powershell
git push origin home_page
```

Team workflow-এ Pull Request ব্যবহার করলে direct merge/push-এর বদলে GitHub-এ `candidate-profile-hardening` থেকে `home_page`-এর জন্য PR তৈরি করাই ভালো।

---

## সমস্যা হলে কী করবেন

### Merge করার আগেই error পাওয়া গেলে

`home_page` তখনো নিরাপদ থাকবে। Feature branch-এই fix করুন:

```powershell
git switch candidate-profile-hardening
```

Fix এবং test করার পরে:

```powershell
git add .
git commit -m "fix: resolve candidate profile verification issue"
git push
```

### সাময়িকভাবে stable branch-এ ফিরে যেতে হলে

Working tree clean নিশ্চিত করে:

```powershell
git switch home_page
```

### Merge ও push-এর পরে সমস্যা ধরা পড়লে

Shared history rewrite করতে `git reset --hard` বা force-push ব্যবহার করবেন না। Merge commit identify করুন:

```powershell
git log --oneline --merges -5
```

তারপর team-এর সঙ্গে coordinate করে merge commit revert করুন:

```powershell
git revert -m 1 <merge-commit-hash>
git push origin home_page
```

Database migration rollback code rollback-এর থেকে আলাদা বিষয়। বিশেষ করে private-resume migration rollback করলেও files public storage-এ ফিরে যাবে না। Database/storage backup ব্যবহার করে পরিকল্পিতভাবে recovery করুন।

---

## Final quick checklist

- [ ] আলাদা `candidate-profile-hardening` branch তৈরি হয়েছে
- [ ] `.phpunit.result.cache` commit হয়নি
- [ ] Feature branch GitHub-এ push হয়েছে
- [ ] Office computer-এ branch cleanভাবে pull হয়েছে
- [ ] Database এবং resume storage backup নেওয়া হয়েছে
- [ ] তিনটি migration সঠিক order-এ run হয়েছে
- [ ] Candidate tests pass করেছে
- [ ] Feature tests pass করেছে
- [ ] Browser manual checks সম্পন্ন হয়েছে
- [ ] Latest `home_page` pull করা হয়েছে
- [ ] Conflict থাকলে review করে resolve করা হয়েছে
- [ ] Merge-এর পরে candidate tests আবার pass করেছে
- [ ] তারপরেই `home_page` push করা হয়েছে

