# Security Policy

## Overview

The **tool_participantscustomfilter** plugin is an administration tool for Moodle that adds custom profile field filtering to the participants listing page. This document outlines our security practices, supported versions, and how to report vulnerabilities.

## Supported Versions

| Version | Release Date | Moodle Requirement | Security Support Until |
|---------|--------------|--------------------|------------------------|
| 1.0.0   | 2026-03-04   | 4.5.x - 5.1.x      | TBD                    |

Security patches will be provided for the currently supported versions. Older versions will not receive security backports unless critical vulnerabilities are discovered.

## Security Properties

### Capability Requirements

This plugin enforces Moodle capability checks:
- **`moodle/course:viewparticipants`** - Required to access the filter functionality

Only users with explicit permission to view course participants can use the custom field filter.

### Input Validation & Sanitization

All user inputs are validated and sanitized using Moodle's parameter cleaning API:
- **`filtercustomfield`**: `PARAM_ALPHANUMEXT` - Alphanumeric and underscore characters only
- **`filtervalue`**: `PARAM_TEXT` - Basic text filtering with HTML entities escaped

Custom profile field names are validated against the database before processing to prevent injection attacks.

### Context Isolation

- Filters operate at the course context level (`context_course`)
- A user cannot filter participants in a course they don't have access to
- The plugin validates course ID and context existence before processing

### Data Access Control

- Participant lists are filtered based on the viewing user's capabilities
- No user data is exposed beyond what the user already has permission to view
- Profile field filtering respects user profile visibility settings

## Security Considerations

### 1. Capability-Based Access

The plugin respects Moodle's role-based access control. Access to participant filtering is tied to the standard `viewparticipants` capability, ensuring no access elevation occurs.

### 2. SQL Injection Prevention

All database queries use Moodle's database abstraction layer with prepared statements, preventing SQL injection.

### 3. Cross-Site Scripting (XSS) Prevention

- Filter values are escaped through Moodle's text processing functions
- Templates render user input through Mustache, which auto-escapes HTML
- No inline JavaScript is used for dynamic content rendering

### 4. Cross-Site Request Forgery (CSRF) Protection

The plugin operates within Moodle's session framework and respects `require_sesskey()` where applicable.

## Known Limitations

1. **Filter Performance**: Filtering large participant lists with high-cardinality custom fields may impact performance. Administrators should monitor performance impact.

2. **Profile Field Visibility**: The plugin filters based on field content but respects Moodle's profile visibility settings. If a field is marked as private, it is not available for filtering.

3. **Third-party Custom Fields**: Filtering only supports standard Moodle custom profile fields. Third-party field types are supported only if they store data in the standard structure.

## Reporting a Vulnerability

**⚠️ Please DO NOT create a public GitHub issue for security vulnerabilities.**

If you discover a security vulnerability, please report it responsibly to the maintainers:

### Contact Information
- **Email**: kelsoncm+tool_participantscustomfilter@gmail.com
- **Subject**: `[SECURITY] tool_participantscustomfilter Vulnerability`

### What to Include
1. Description of the vulnerability
2. Steps to reproduce (if applicable)
3. Impact assessment (e.g., data exposure, privilege escalation)
4. Affected versions
5. Suggested remediation (optional)

### Response Timeline
- **Initial Response**: Within 48 hours
- **Assessment**: Within 1 week
- **Patch Development**: Depends on severity
- **Disclosure**: After patch release, typically within 30 days

### Severity Levels

| Level    | Description                                                       | Examples                                     |
|----------|-------------------------------------------------------------------|----------------------------------------------|
| Critical | Allows unauthorized access to sensitive data or system compromise | SQL injection, privilege escalation, RCE     |
| High     | Allows unauthorized actions or significant data exposure          | XSS, CSRF, access control bypass             |
| Medium   | Limited unauthorized access or information disclosure             | Information leakage, certain edge-case vulns |
| Low      | Minimal security impact or requires specific conditions           | Denial of service on small scale             |

## Security Best Practices for Administrators

### Installation & Updates
1. Install from official Moodle Plugin Directory or verified source
2. Keep Moodle updated to the latest stable release
3. Install security patches immediately upon availability
4. Test updates in a staging environment first

### Configuration
1. Audit which users have `viewparticipants` capability
2. Restrict this capability to trusted roles only
3. Monitor administrative logs for unusual filter queries
4. Consider restricting custom profile fields visible to students

### Monitoring
- Check Moodle logs for failed access attempts
- Review participated user lists periodically
- Monitor database performance impact of filtering operations

## Development & Code Review

All contributions follow these security principles:

1. **No Hardcoded Credentials**: Sensitive data is never stored in repository
2. **Capability Checks**: Every user-facing action requires capability verification
3. **Input Validation**: All user inputs are validated and sanitized
4. **Prepared Statements**: All database queries use parameterized queries
5. **Error Handling**: Errors are logged but not exposed to users
6. **Code Review**: All changes undergo security review before merge

## Dependencies

This plugin has minimal dependencies:
- Moodle core (≥ 4.5.0)
- PHP 8.1+

No external libraries are bundled. Security patches for Moodle itself are handled by Moodle maintainers.

## License

This plugin is licensed under the [GNU General Public License v3.0 or later](LICENSE).

## Contact & Support

- **GitHub Repository**: https://github.com/IFRN/moodle-tool_participantscustomfilter
- **Bug Reports**: GitHub Issues (non-security only)
- **Security Reports**: See "Reporting a Vulnerability" section above

---

**Last Updated**: 2026-03-04  
**Status**: Active  
**Maintainer**: [KelsonCM](https://github.com/kelsoncm/)